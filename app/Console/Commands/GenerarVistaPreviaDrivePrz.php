<?php

namespace App\Console\Commands;

use App\Models\Documento;
use App\Support\DrivePrzAjustes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickDraw;
use ImagickPixel;

class GenerarVistaPreviaDrivePrz extends Command
{
    protected $signature = 'drive-prz:generar-preview {documento}';

    protected $description =
        'Genera imágenes privadas de un PDF confiable para enlaces externos';

    public function handle(): int
    {
        $id = (int) $this->argument('documento');
        $documento = Documento::findOrFail($id);

        $origen = Storage::disk('documentos_privados');
        $destino = Storage::disk('drive_prz_previews');

        if (! $origen->exists($documento->ruta_archivo)) {
            $this->error('Archivo original no encontrado.');
            return self::FAILURE;
        }

        if ($origen->mimeType($documento->ruta_archivo) !== 'application/pdf') {
            $this->error('Solo se admiten PDF.');
            return self::FAILURE;
        }

        $tamano = $origen->size($documento->ruta_archivo);

        if ($tamano > 15 * 1024 * 1024) {
            $this->error('PDF demasiado grande.');
            return self::FAILURE;
        }

        $ruta = $origen->path($documento->ruta_archivo);

        if (! is_file($ruta)) {
            $this->error('Se requiere almacenamiento local.');
            return self::FAILURE;
        }

        // Se mantiene la revisión basada en el PDF original para conservar
        // la compatibilidad con DrivePrzEnlaceController::datosPreview().
        $revision = hash(
            'sha256',
            $documento->ruta_archivo . '|' .
                $tamano . '|' .
                $origen->lastModified($documento->ruta_archivo)
        );

        $directorio = $documento->id . '/' . $revision;

        // Estos límites y el número máximo de páginas corresponden a la
        // configuración existente. Procesar solo PDF confiables.
        Imagick::setResourceLimit(Imagick::RESOURCETYPE_MEMORY, 128 * 1024 * 1024);
        Imagick::setResourceLimit(Imagick::RESOURCETYPE_MAP, 256 * 1024 * 1024);
        Imagick::setResourceLimit(Imagick::RESOURCETYPE_DISK, 1024 * 1024 * 1024);
        Imagick::setResourceLimit(Imagick::RESOURCETYPE_THREAD, 1);

        try {
            $inspector = new Imagick();

            try {
                $inspector->pingImage($ruta);
                $total = $inspector->getNumberImages();
            } finally {
                $inspector->clear();
                $inspector->destroy();
            }

            if ($total < 1 || $total > 20) {
                $this->error('Solo se admiten PDF de 1 a 20 páginas.');
                return self::FAILURE;
            }

            // Leer los ajustes una vez por documento para que todas sus
            // páginas tengan las mismas dos marcas de agua.
            $ajustesMarca = DrivePrzAjustes::marcaAgua();
            $archivos = [];

            for ($pagina = 0; $pagina < $total; $pagina++) {
                $imagen = new Imagick();

                try {
                    $imagen->setResolution(72, 72);
                    $imagen->readImage($ruta . '[' . $pagina . ']');
                    $imagen->setImageBackgroundColor('white');
                    $imagen = $imagen->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

                    $this->aplicarMarcaDeAgua($imagen, $ajustesMarca);

                    $imagen->setImageFormat('jpeg');
                    $imagen->setImageCompressionQuality(78);
                    $imagen->stripImage();

                    $nombre = sprintf('pagina-%03d.jpg', $pagina + 1);
                    $guardado = $destino->put(
                        $directorio . '/' . $nombre,
                        $imagen->getImageBlob()
                    );

                    if (! $guardado) {
                        throw new \RuntimeException('No se pudo guardar la página ' . ($pagina + 1));
                    }

                    $archivos[] = $nombre;
                    $this->info('Página ' . ($pagina + 1) . '/' . $total);
                } finally {
                    $imagen->clear();
                    $imagen->destroy();
                }
            }

            // El manifiesto solo se publica una vez generadas todas las páginas.
            $guardado = $destino->put(
                $directorio . '/manifest.json',
                json_encode([
                    'documento_id' => $documento->id,
                    'revision' => $revision,
                    'paginas' => $archivos,
                    'creado_at' => now()->toIso8601String(),
                ], JSON_THROW_ON_ERROR)
            );

            if (! $guardado) {
                throw new \RuntimeException('No se pudo guardar el manifiesto.');
            }

            $this->info('Vista previa generada correctamente.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            report($e);
            $this->error('ERROR AL GENERAR VISTA PREVIA');
            $this->error('Tipo: ' . get_class($e));
            $this->error('Mensaje: ' . $e->getMessage());
            $this->error('Archivo: ' . $e->getFile());
            $this->error('Línea: ' . $e->getLine());

            // Se mantiene la limpieza del directorio que usaba el generador.
            $destino->deleteDirectory($directorio);
            return self::FAILURE;
        }
    }

    /**
     * Dibuja los textos como un único bloque centrado, sobre una capa
     * transparente, y rota la capa completa antes de componerla.
     * Así la rotación no desplaza las letras hacia la esquina.
     */
    private function aplicarMarcaDeAgua(Imagick $imagen, array $ajustes): void
    {
        $principal = trim((string) ($ajustes['texto'] ?? ''));
        $secundario = trim((string) ($ajustes['texto_secundario'] ?? ''));

        if ($principal === '' && $secundario === '') {
            return;
        }

        $ancho = $imagen->getImageWidth();
        $alto = $imagen->getImageHeight();
        $base = ! empty($ajustes['usar_tamano_manual']) && ! empty($ajustes['tamano'])
            ? (int) $ajustes['tamano']
            : max(14, min(34, (int) ($ancho / 24)));

        $angulo = (float) ($ajustes['angulo'] ?? -25);
        $color = new ImagickPixel($this->hexAColorRgba(
            (string) ($ajustes['color'] ?? '#14263D'),
            (int) ($ajustes['opacidad'] ?? 22)
        ));

        // Cada línea secundaria ocupa su propia fila.
        $lineas = [];
        if ($principal !== '') {
            $lineas[] = ['texto' => $principal, 'tamano' => $base];
        }
        if ($secundario !== '') {
            foreach (preg_split('/\R/u', $secundario) ?: [] as $linea) {
                $linea = trim($linea);
                if ($linea !== '') {
                    $lineas[] = ['texto' => $linea, 'tamano' => max(12, (int) round($base * 0.72))];
                }
            }
        }
        if ($lineas === []) {
            return;
        }

        // Trabajamos sobre una capa transparente del tamaño de la página.
        // Rotamos la capa de texto, no las coordenadas del propio annotateImage.
        $capa = new Imagick();
        try {
            $capa->newImage($ancho, $alto, new ImagickPixel('transparent'));
            $capa->setImageFormat('png');

            // Mantener el texto dentro de un área segura, también al rotarlo.
            // A 45° o más la diagonal exige márgenes amplios.
            $radianes = deg2rad($angulo);
            $cos = abs(cos($radianes));
            $sin = abs(sin($radianes));
            $limiteAncho = max(80.0, ($ancho * 0.78) / max(0.25, $cos + $sin * 0.25));
            $limiteAlto = max(40.0, $alto * 0.45);

            $medidor = new ImagickDraw();
            $medidor->setTextAlignment(Imagick::ALIGN_CENTER);
            $medidor->setGravity(Imagick::GRAVITY_NORTHWEST);
            $medidas = [];
            $totalAlto = 0.0;
            $anchoMax = 0.0;

            foreach ($lineas as $linea) {
                $tamano = $linea['tamano'];
                $medidor->setFontSize($tamano);
                $metrica = $capa->queryFontMetrics($medidor, $linea['texto']);
                $medidas[] = ['texto' => $linea['texto'], 'tamano' => $tamano, 'altura' => max($tamano * 1.3, (float) ($metrica['textHeight'] ?? $tamano))];
                $anchoMax = max($anchoMax, (float) ($metrica['textWidth'] ?? 0));
                $totalAlto += end($medidas)['altura'];
            }
            $medidor->clear();

            $escala = min(1.0, $limiteAncho / max(1.0, $anchoMax), $limiteAlto / max(1.0, $totalAlto));
            if ($escala < 1.0) {
                foreach ($medidas as &$linea) {
                    $linea['tamano'] = max(6, $linea['tamano'] * $escala);
                    $linea['altura'] *= $escala;
                }
                unset($linea);
                $totalAlto *= $escala;
            }

            $draw = new ImagickDraw();
            try {
                $draw->setFillColor($color);
                $draw->setTextAlignment(Imagick::ALIGN_CENTER);
                $draw->setGravity(Imagick::GRAVITY_NORTHWEST);

                // Distribuir el bloque alrededor del centro de la página.
                $y = ($alto - $totalAlto) / 2;
                foreach ($medidas as $linea) {
                    $draw->setFontSize($linea['tamano']);
                    $y += $linea['altura'];
                    $capa->annotateImage($draw, $ancho / 2, $y, 0, $linea['texto']);
                }
            } finally {
                $draw->clear();
            }

            // Rotar conservando el mismo centro geométrico.
            // rotateImage expande el lienzo: centramos el resultado en
            // la página original usando la diferencia de dimensiones.
            if (abs($angulo) > 0.001) {
                $capa->rotateImage(new ImagickPixel('transparent'), $angulo);
            }

            $x = (int) round(($ancho - $capa->getImageWidth()) / 2);
            $y = (int) round(($alto - $capa->getImageHeight()) / 2);
            $imagen->compositeImage($capa, Imagick::COMPOSITE_OVER, $x, $y);
        } finally {
            $capa->clear();
            $capa->destroy();
        }
    }

    private function hexAColorRgba(string $hex, int $opacidad): string
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = preg_replace('/(.)/', '$1$1', $hex);
        }

        if (! preg_match('/^[A-Fa-f0-9]{6}$/', $hex)) {
            $hex = '14263D';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $alpha = max(0, min(100, $opacidad)) / 100;

        return "rgba({$r},{$g},{$b},{$alpha})";
    }
}

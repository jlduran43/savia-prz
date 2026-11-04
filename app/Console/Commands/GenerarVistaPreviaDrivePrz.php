<?php

namespace App\Console\Commands;

use App\Models\Documento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickException;
use ImagickDraw;
use ImagickPixel;
use App\Support\DrivePrzAjustes;

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

        if (
            $origen->mimeType($documento->ruta_archivo)
            !== 'application/pdf'
        ) {
            $this->error('Solo se admiten PDF.');
            return self::FAILURE;
        }

        $tamano = $origen->size($documento->ruta_archivo);

        // Límite inicial: 15 MB
        if ($tamano > 15 * 1024 * 1024) {
            $this->error('PDF demasiado grande.');
            return self::FAILURE;
        }

        $ruta = $origen->path($documento->ruta_archivo);

        if (! is_file($ruta)) {
            $this->error('Se requiere almacenamiento local.');
            return self::FAILURE;
        }

        // Evita reutilizar vistas previas de versiones anteriores
        $revision = hash(
            'sha256',
            $documento->ruta_archivo . '|' .
                $tamano . '|' .
                $origen->lastModified($documento->ruta_archivo)
        );

        $directorio = $documento->id . '/' . $revision;

        // Imagick ejecuta herramientas de procesamiento PDF.
        // No utilizar con documentos no confiables hasta
        // actualizar y aislar el renderizador.
        Imagick::setResourceLimit(
            Imagick::RESOURCETYPE_MEMORY,
            128 * 1024 * 1024
        );

        Imagick::setResourceLimit(
            Imagick::RESOURCETYPE_MAP,
            256 * 1024 * 1024
        );

        Imagick::setResourceLimit(
            Imagick::RESOURCETYPE_DISK,
            1024 * 1024 * 1024
        );

        Imagick::setResourceLimit(
            Imagick::RESOURCETYPE_THREAD,
            1
        );

        try {
            $inspector = new Imagick();
            $inspector->pingImage($ruta);

            $total = $inspector->getNumberImages();
            $inspector->clear();
            $inspector->destroy();

            if ($total < 1 || $total > 20) {
                $this->error('Solo se admiten PDF de 1 a 20 páginas.');
                return self::FAILURE;
            }

            $archivos = [];

            for ($pagina = 0; $pagina < $total; $pagina++) {

                $imagen = new Imagick();

                try {
                    $imagen->setResolution(72, 72);
                    $imagen->readImage($ruta . '[' . $pagina . ']');

                    $imagen->setImageBackgroundColor('white');
                    $imagen = $imagen->mergeImageLayers(
                        Imagick::LAYERMETHOD_FLATTEN
                    );

                    $ajustesMarca = DrivePrzAjustes::marcaAgua();

                    $textoPrincipal = trim((string) ($ajustesMarca['texto'] ?? ''));
                    $textoSecundario = trim((string) ($ajustesMarca['texto_secundario'] ?? ''));

                    $textoCompleto = $textoPrincipal;

                    if ($textoSecundario !== '') {
                        $textoCompleto .= "\n" . $textoSecundario;
                    }

                    // Marca de agua incrustada en los píxeles
                    $marca = new ImagickDraw();

                    $marca->setFillColor(
                        new ImagickPixel(
                            $this->hexAColorRgba(
                                $ajustesMarca['color'],
                                $ajustesMarca['opacidad']
                            )
                        )
                    );

                    $fontSize = $ajustesMarca['usar_tamano_manual']
                        && !empty($ajustesMarca['tamano'])
                        ? (int) $ajustesMarca['tamano']
                        : max(14, min(34, (int) ($imagen->getImageWidth() / 24)));

                    $marca->setFontSize($fontSize);
                    $marca->setTextAlignment(\Imagick::ALIGN_CENTER);
                    $marca->setGravity(Imagick::GRAVITY_CENTER);

                    $imagen->annotateImage(
                        $marca,
                        0,
                        0,
                        (float) $ajustesMarca['angulo'],
                        $textoCompleto
                    );

                    $marca->clear();

                    $marca->clear();

                    // Preparar el archivo final
                    $imagen->setImageFormat('jpeg');
                    $imagen->setImageCompressionQuality(78);
                    $imagen->stripImage();

                    $nombre = sprintf('pagina-%03d.jpg', $pagina + 1);

                    $destino->put(
                        $directorio . '/' . $nombre,
                        $imagen->getImageBlob()
                    );

                    $archivos[] = $nombre;

                    $this->info(
                        'Página ' . ($pagina + 1) . '/' . $total
                    );
                } finally {
                    $imagen->clear();
                    $imagen->destroy();
                }
            }

            // El manifiesto se escribe al final:
            // una conversión interrumpida no se publica como completa.
            $destino->put(
                $directorio . '/manifest.json',
                json_encode([
                    'documento_id' => $documento->id,
                    'revision' => $revision,
                    'paginas' => $archivos,
                    'creado_at' => now()->toIso8601String(),
                ], JSON_THROW_ON_ERROR)
            );

            $this->info('Vista previa generada correctamente.');

            return self::SUCCESS;
        } catch (\Throwable $e) {

            report($e);

            $this->error('ERROR AL GENERAR VISTA PREVIA');
            $this->error('Tipo: ' . get_class($e));
            $this->error('Mensaje: ' . $e->getMessage());
            $this->error('Archivo: ' . $e->getFile());
            $this->error('Línea: ' . $e->getLine());

            $destino->deleteDirectory($directorio);

            return self::FAILURE;
        }
    }

    private function hexAColorRgba(string $hex, int $opacidad): string
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = preg_replace('/(.)/', '$1$1', $hex);
        }

        if (!preg_match('/^[A-Fa-f0-9]{6}$/', $hex)) {
            $hex = '14263D';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $alpha = max(0, min(100, $opacidad)) / 100;

        return "rgba({$r},{$g},{$b},{$alpha})";
    }
}

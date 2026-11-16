<?php
namespace App\Console\Commands;
use App\Models\Documento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Imagick;
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
            $archivos = [];
            for ($pagina = 0; $pagina < $total; $pagina++) {
                $imagen = new Imagick();
                try {
                    $imagen->setResolution(72, 72);
                    $imagen->readImage($ruta . '[' . $pagina . ']');
                    $imagen->setImageBackgroundColor('white');
                    $imagen = $imagen->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
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
}

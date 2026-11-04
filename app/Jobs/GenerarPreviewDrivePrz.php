<?php

namespace App\Jobs;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class GenerarPreviewDrivePrz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public int $documentoId
    ) {
        $this->onConnection('database');
        $this->onQueue('drive-prz-previews');
    }

    public function backoff(): array
    {
        return [60, 180];
    }

    public function handle(): void
    {
        if (!config('drive-prz.preview_enabled', false)) {
            return;
        }

        $documento = Documento::find($this->documentoId);

        if (!$documento) {
            return;
        }

        $extension = strtolower(pathinfo(
            $documento->nombre_original ?? '',
            PATHINFO_EXTENSION
        ));

        if ($extension !== 'pdf') {
            return;
        }

        // Registrar que comenzó el procesamiento.
        Documento::whereKey($documento->id)->update([
            'preview_estado' => 'PROCESANDO',
            'preview_error' => null,
            'preview_iniciado_at' => now(),
            'preview_procesado_at' => null,
        ]);

        $disco = Storage::disk('documentos_privados');

        if (
            !$documento->ruta_archivo ||
            !$disco->exists($documento->ruta_archivo)
        ) {
            throw new RuntimeException(
                'El archivo PDF no está disponible.'
            );
        }

        if (
            $disco->mimeType($documento->ruta_archivo)
            !== 'application/pdf'
        ) {
            throw new RuntimeException(
                'El archivo no corresponde a un PDF válido.'
            );
        }

        if (
            $disco->size($documento->ruta_archivo)
            > 15 * 1024 * 1024
        ) {
            throw new RuntimeException(
                'El PDF supera el límite de 15 MB.'
            );
        }

        // Conservar el generador existente con
        // la marca de agua permanente.
        $resultado = Artisan::call(
            'drive-prz:generar-preview',
            ['documento' => $documento->id]
        );

        if ($resultado !== 0) {
            throw new RuntimeException(
                'No fue posible generar la vista previa del PDF.'
            );
        }

        // El comando finalizó correctamente.
        Documento::whereKey($documento->id)->update([
            'preview_estado' => 'LISTO',
            'preview_error' => null,
            'preview_procesado_at' => now(),
            'preview_iniciado_at' => null,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        // Laravel llama este método cuando el Job
        // falla definitivamente.
        Documento::whereKey($this->documentoId)->update([
            'preview_estado' => 'ERROR',
            'preview_error' => $exception
                ? mb_substr($exception->getMessage(), 0, 1000)
                : 'No fue posible generar la vista previa.',
            'preview_procesado_at' => null,
            'preview_iniciado_at' => null,
        ]);
    }
}

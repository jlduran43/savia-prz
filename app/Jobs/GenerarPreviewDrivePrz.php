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
        // La cola no debe convertir documentos mientras
        // el renderizador no esté habilitado expresamente.
        if (!config('drive-prz.preview_enabled', false)) {
            return;
        }

        $documento = Documento::find($this->documentoId);

        if (!$documento) {
            return;
        }

        $disco = Storage::disk('documentos_privados');

        // El archivo puede no haberse terminado de guardar.
        if (!$documento->ruta_archivo ||
            !$disco->exists($documento->ruta_archivo)) {
            throw new RuntimeException('Archivo todavía no disponible.');
        }

        if ($disco->mimeType($documento->ruta_archivo)
            !== 'application/pdf') {
            return;
        }

        $resultado = Artisan::call(
            'drive-prz:generar-preview',
            ['documento' => $documento->id]
        );

        if ($resultado !== 0) {
            throw new RuntimeException(
                'Falló la vista previa del documento ' . $documento->id
            );
        }
    }
}
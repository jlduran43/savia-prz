<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RevisarPreviewsDrivePrz extends Command
{
    protected $signature = 'drive-prz:revisar-previews';

    protected $description =
        'Detectar vistas previas que llevan demasiado tiempo procesándose.';

    public function handle(): int
    {
        // Mantener los límites de tiempo coordinados
        // con timeout del trabajador y retry_after.
        $limite = now()->subMinutes(15);

        $actualizados = DB::table('documentos')
            ->where('preview_estado', 'PROCESANDO')
            ->whereNotNull('preview_iniciado_at')
            ->where('preview_iniciado_at', '<', $limite)
            ->update([
                'preview_estado' => 'ERROR',
                'preview_error' =>
                    'El procesamiento excedió el tiempo esperado. ' .
                    'Se requiere revisión.',
                'preview_iniciado_at' => null,
                'preview_procesado_at' => null,
            ]);

        $this->info(
            "Documentos marcados para revisión: {$actualizados}"
        );

        return self::SUCCESS;
    }
}

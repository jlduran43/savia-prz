<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RevisarPreviewsDrivePrz extends Command
{
    protected $signature = 'drive-prz:revisar-previews';

    protected $description =
        'Detectar vistas previas que podrían estar interrumpidas.';

    public function handle(): int
    {
        $limite = now()->subMinutes(15);

        $documentos = DB::table('documentos')
            ->where('preview_estado', 'PROCESANDO')
            ->whereNotNull('preview_iniciado_at')
            ->where('preview_iniciado_at', '<', $limite)
            ->select([
                'id',
                'nombre_original',
                'preview_estado',
                'preview_iniciado_at',
            ])
            ->orderBy('preview_iniciado_at')
            ->get();

        if ($documentos->isEmpty()) {
            $this->info(
                'No se encontraron vistas previas atrasadas.'
            );

            return self::SUCCESS;
        }

        $this->warn(
            'Documentos que requieren revisión: ' .
            $documentos->count()
        );

        $this->table(
            ['ID', 'Documento', 'Estado', 'Inicio'],
            $documentos->map(fn ($documento) => [
                $documento->id,
                $documento->nombre_original,
                $documento->preview_estado,
                $documento->preview_iniciado_at,
            ])->all()
        );

        $this->warn(
            'No se modificaron documentos. ' .
            'Comprueba los trabajos pendientes y fallidos ' .
            'antes de realizar una recuperación.'
        );

        return self::SUCCESS;
    }
}

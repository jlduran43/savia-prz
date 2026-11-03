<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DrivePrzAuditoria
{
    public static function registrar(
        Request $request,
        string $evento,
        ?int $documentoId = null,
        ?int $enlaceId = null,
        ?int $pagina = null
    ): void {

        try {
            // Identificador pseudónimo por día.
            // No almacenamos directamente la IP.
            $ip = $request->ip() ?? '';

            $secreto = config('app.key');

            $clienteHash = $ip !== ''
                ? hash_hmac(
                    'sha256',
                    now()->toDateString() . '|' . $ip,
                    $secreto
                )
                : null;

            DB::table('drive_prz_accesos')->insert([
                'documento_id' => $documentoId,
                'enlace_id' => $enlaceId,
                'evento' => $evento,
                'pagina' => $pagina,
                'cliente_hash' => $clienteHash,
                'user_agent' => substr(
                    $request->userAgent() ?? '',
                    0,
                    255
                ),
                'created_at' => now(),
            ]);

        } catch (Throwable $e) {
            // Una falla de auditoría no debe exponer
            // detalles internos a los visitantes.
            report($e);
        }
    }
}
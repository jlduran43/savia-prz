<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DrivePrzAvisos
{
    private static function registrar(
        int $destinatarioId,
        int $remitenteId,
        string $tipo,
        int $elementoId,
        string $nombre,
        string $evento,
        string $mensaje
    ): void {
        if ($destinatarioId === $remitenteId) {
            return;
        }

        if (!in_array($tipo, ['files', 'folders'], true)) {
            throw new InvalidArgumentException(
                'Tipo de elemento inválido.'
            );
        }

        DB::table('drive_prz_notificaciones')->insert([
            'usuario_id' => $destinatarioId,
            'remitente_id' => $remitenteId,
            'tipo' => $evento,
            'elemento_tipo' => $tipo,
            'elemento_id' => $elementoId,
            'elemento_nombre' => mb_substr($nombre, 0, 255),
            'mensaje' => mb_substr($mensaje, 0, 1000),
            'leida_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function documentoCompartido(
        int $destinatarioId,
        int $remitenteId,
        string $tipo,
        int $elementoId,
        string $nombre
    ): void {
        $mensaje = $tipo === 'folders'
            ? "Compartió contigo la carpeta {$nombre}"
            : "Compartió contigo el archivo {$nombre}";

        self::registrar(
            $destinatarioId,
            $remitenteId,
            $tipo,
            $elementoId,
            $nombre,
            'DOCUMENTO_COMPARTIDO',
            $mensaje
        );
    }

    public static function permisosActualizados(
        int $destinatarioId,
        int $remitenteId,
        string $tipo,
        int $elementoId,
        string $nombre,
        string $permiso
    ): void {
        $descripcion = $tipo === 'folders'
            ? 'la carpeta'
            : 'el archivo';

        $mensaje = "Actualizó tus permisos sobre "
            . "{$descripcion} {$nombre}. "
            . "Nuevo acceso: {$permiso}.";

        self::registrar(
            $destinatarioId,
            $remitenteId,
            $tipo,
            $elementoId,
            $nombre,
            'PERMISOS_ACTUALIZADOS',
            $mensaje
        );
    }
}

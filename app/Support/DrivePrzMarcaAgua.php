<?php

namespace App\Support;

use App\Models\DrivePrzConfiguracion;

class DrivePrzMarcaAgua
{
    public static function activa(): bool
    {
        $configuracion = DrivePrzConfiguracion::query()->first();

        // Si no existe configuración, no mostrar marca de agua.
        return (bool) ($configuracion?->marca_agua_activa ?? false);
    }

    public static function configuracion(): array
    {
        $configuracion = DrivePrzConfiguracion::query()->first();

        return [
            'activa' => (bool) (
                $configuracion?->marca_agua_activa ?? false
            ),

            'texto' => $configuracion?->marca_agua_texto
                ?? 'DRIVE PRZ - SOLO LECTURA',

            'texto_secundario' =>
            $configuracion?->marca_agua_texto_secundario ?? '',

            'color' => $configuracion?->marca_agua_color
                ?? '#14263D',

            'opacidad' => (int) (
                $configuracion?->marca_agua_opacidad ?? 22
            ),

            'angulo' => (int) (
                $configuracion?->marca_agua_angulo ?? -25
            ),

            'usar_tamano_manual' => (bool) (
                $configuracion?->usar_tamano_manual ?? false
            ),

            'tamano' => $configuracion?->marca_agua_tamano,
        ];
    }

    public static function revision(): string
    {
        $configuracion = self::configuracion();

        // Cuando esté desactivada, no necesitamos
        // considerar textos, color ni tamaño.
        if (! $configuracion['activa']) {
            return hash('sha256', 'marca-agua-v2:desactivada');
        }

        return hash(
            'sha256',
            json_encode(
                $configuracion,
                JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_THROW_ON_ERROR
            )
        );
    }
}

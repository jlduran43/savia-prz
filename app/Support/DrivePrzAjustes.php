<?php

namespace App\Support;

use App\Models\DrivePrzConfiguracion;
use Illuminate\Support\Facades\Cache;

class DrivePrzAjustes
{
    public static function marcaAgua(): array
    {
        return Cache::rememberForever(
            'drive_prz_configuracion_marca_agua',
            function () {
                $config = DrivePrzConfiguracion::query()->first();

                if (!$config) {
                    $config = DrivePrzConfiguracion::query()->create([
                        'marca_agua_texto' => 'DRIVE PRZ - SOLO LECTURA',
                        'marca_agua_texto_secundario' => '',
                        'marca_agua_color' => '#14263D',
                        'marca_agua_opacidad' => 22,
                        'marca_agua_angulo' => -25,
                        'usar_tamano_manual' => false,
                        'marca_agua_tamano' => null,
                    ]);
                }

                return [
                    'texto' => $config->marca_agua_texto,
                    'texto_secundario' =>
                        $config->marca_agua_texto_secundario ?? '',
                    'color' => $config->marca_agua_color,
                    'opacidad' => $config->marca_agua_opacidad,
                    'angulo' => $config->marca_agua_angulo,
                    'usar_tamano_manual' => $config->usar_tamano_manual,
                    'tamano' => $config->marca_agua_tamano,
                ];
            }
        );
    }

    public static function limpiarCache(): void
    {
        Cache::forget('drive_prz_configuracion_marca_agua');
    }
}

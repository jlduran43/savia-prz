<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivePrzConfiguracion extends Model
{
    protected $table = 'drive_prz_configuraciones';

    protected $fillable = [
        'marca_agua_texto',
        'marca_agua_texto_secundario',
        'marca_agua_color',
        'marca_agua_opacidad',
        'marca_agua_angulo',
        'usar_tamano_manual',
        'marca_agua_tamano',
    ];

    protected $casts = [
        'marca_agua_opacidad' => 'integer',
        'marca_agua_angulo' => 'integer',
        'usar_tamano_manual' => 'boolean',
        'marca_agua_tamano' => 'integer',
    ];
}

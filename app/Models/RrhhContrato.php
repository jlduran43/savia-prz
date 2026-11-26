<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RrhhContrato extends Model
{
    protected $table = 'rrhh_contratos';

    protected $fillable = [
        'trabajador_id',

        // Información contractual
        'tipo_contrato',
        'tipo_jornada',
        'detalle_part_time',
        'otra_jornada',
        'jornada_contractual',

        // Información organizacional
        'area',
        'cargo',
        'jefatura_directa',

        // Situación laboral
        'estado_vinculo',
        'situacion_administrativa',

        // Fechas
        'fecha_inicio',
        'fecha_termino',
        'fecha_desvinculacion',

        // Información complementaria
        'ct_extras',
        'hon_extras',
        'observacion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_termino' => 'date',
        'fecha_desvinculacion' => 'date',
        'ct_extras' => 'array',
        'hon_extras' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Trabajador asociado
    |--------------------------------------------------------------------------
    */

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(
            RrhhTrabajador::class,
            'trabajador_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Movimientos asociados al contrato
    |--------------------------------------------------------------------------
    */

    public function historialLaboral(): HasMany
    {
        return $this->hasMany(
            RrhhHistorialLaboral::class,
            'contrato_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Identificar contrato indefinido
    |--------------------------------------------------------------------------
    */

    public function getEsIndefinidoAttribute(): bool
    {
        return $this->tipo_contrato === 'indefinido';
    }
}
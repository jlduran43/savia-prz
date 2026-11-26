<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RrhhHistorialLaboral extends Model
{
    protected $table = 'rrhh_historial_laboral';

    protected $fillable = [
        'trabajador_id',
        'contrato_id',
        'registrado_por',
        'tipo_movimiento',
        'fecha_efectiva',
        'antes',
        'despues',
        'motivo',
    ];

    protected $casts = [
        'fecha_efectiva' => 'date',
        'antes' => 'array',
        'despues' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Tipos de movimientos laborales
    |--------------------------------------------------------------------------
    */

    public const INGRESO = 'INGRESO';

    public const NUEVO_CONTRATO = 'NUEVO_CONTRATO';

    public const RENOVACION_CONTRATO = 'RENOVACION_CONTRATO';

    public const CAMBIO_CONTRATO = 'CAMBIO_CONTRATO';

    public const CAMBIO_CARGO = 'CAMBIO_CARGO';

    public const CAMBIO_AREA = 'CAMBIO_AREA';

    public const CAMBIO_JEFATURA = 'CAMBIO_JEFATURA';

    public const CAMBIO_JORNADA = 'CAMBIO_JORNADA';

    public const DESVINCULACION = 'DESVINCULACION';

    public const REINGRESO = 'REINGRESO';

    public const ACTUALIZACION_FICHA = 'ACTUALIZACION_FICHA';

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
    | Contrato asociado
    |--------------------------------------------------------------------------
    */

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(
            RrhhContrato::class,
            'contrato_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Usuario que realizó el movimiento
    |--------------------------------------------------------------------------
    */

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'registrado_por'
        );
    }
}
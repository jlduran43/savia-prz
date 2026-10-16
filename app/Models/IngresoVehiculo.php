<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngresoVehiculo extends Model
{
    protected $fillable = [
        'tipo_movimiento',
        'centro_ingreso',
        'responsable',
        'fecha',
        'tipo_vehiculo',
        'hora_inicio',
        'hora_termino',
        'condicion_pago',
        'medio_pago',
        'monto',
        'medio_pago_id',
        'tipo_vehiculo_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function medioPago(): BelongsTo
    {
        return $this->belongsTo(MedioPago::class);
    }

    public function tipoVehiculo(): BelongsTo
    {
        return $this->belongsTo(TipoVehiculo::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedioPago extends Model
{
    protected $table = 'medios_pago';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function ingresosVehiculos(): HasMany
    {
        return $this->hasMany(RegistroIngreso::class);
    }
}

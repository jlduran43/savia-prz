<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoVehiculo extends Model
{
    protected $table = 'tipos_vehiculo';

    protected $fillable = [
        'nombre',
        'precio_base',
        'activo',
    ];

    protected $casts = [
        'precio_base' => 'integer',
        'activo' => 'boolean',
    ];

    public function ingresosVehiculos(): HasMany
    {
        return $this->hasMany(RegistroIngreso::class);
    }
}

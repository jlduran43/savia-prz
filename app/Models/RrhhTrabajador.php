<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RrhhTrabajador extends Model
{
    use SoftDeletes;

    protected $table = 'rrhh_trabajadores';

    protected $fillable = [
        'usuario_id',
        'rut',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'telefono',
        'email',
        'formacion',
        'observacion',
        'fecha_ingreso',
        'fecha_desvinculacion',
        'dependencia',
        'cargo_jefe_inmediato',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'fecha_desvinculacion' => 'date',
        'activo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Usuario asociado a SAVIA PRZ
    |--------------------------------------------------------------------------
    */

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Contratos del trabajador
    |--------------------------------------------------------------------------
    */

    public function contratos(): HasMany
    {
        return $this->hasMany(
            RrhhContrato::class,
            'trabajador_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Historial laboral
    |--------------------------------------------------------------------------
    */

    public function historialLaboral(): HasMany
    {
        return $this->hasMany(
            RrhhHistorialLaboral::class,
            'trabajador_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Nombre completo
    |--------------------------------------------------------------------------
    */

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            implode(' ', array_filter([
                $this->nombres,
                $this->apellido_paterno,
                $this->apellido_materno,
            ]))
        );
    }
}

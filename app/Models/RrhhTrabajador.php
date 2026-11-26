<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'fecha_ingreso',
        'fecha_desvinculacion',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'fecha_desvinculacion' => 'date',
        'activo' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function documentos()
    {
        return $this->hasMany(
            RrhhDocumento::class,
            'trabajador_id'
        );
    }
}
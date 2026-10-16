<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'nombre',
        'activo',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'departamento_id');
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = [
        'nombre',
        'departamento_id',
        'activo',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
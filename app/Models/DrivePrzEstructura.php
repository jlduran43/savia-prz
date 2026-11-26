<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivePrzEstructura extends Model
{
    protected $table = 'drive_prz_estructuras';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'estructura',
    ];
}
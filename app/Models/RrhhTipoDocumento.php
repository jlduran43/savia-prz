<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RrhhTipoDocumento extends Model
{
    protected $table = 'rrhh_tipos_documentos';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'requiere_periodo',
        'activo',
        'orden',
    ];

    protected $casts = [
        'requiere_periodo' => 'boolean',
        'activo' => 'boolean',
    ];

    public function documentos()
    {
        return $this->hasMany(
            RrhhDocumento::class,
            'tipo_documento_id'
        );
    }
}
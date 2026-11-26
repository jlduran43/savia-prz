<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RrhhDocumento extends Model
{
    use SoftDeletes;

    protected $table = 'rrhh_documentos';

    protected $fillable = [
        'trabajador_id',
        'tipo_documento_id',
        'nombre_original',
        'nombre_almacenado',
        'ruta_archivo',
        'mime_type',
        'tamano',
        'anio',
        'mes',
        'observaciones',
        'subido_por',
    ];

    protected $hidden = [
        'ruta_archivo',
        'nombre_almacenado',
    ];

    public function trabajador()
    {
        return $this->belongsTo(
            RrhhTrabajador::class,
            'trabajador_id'
        );
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(
            RrhhTipoDocumento::class,
            'tipo_documento_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}
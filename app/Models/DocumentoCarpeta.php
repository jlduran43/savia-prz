<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentoCarpeta extends Model
{
    use SoftDeletes;

    protected $table = 'documento_carpetas';

    protected $fillable = [
        'nombre',
        'carpeta_padre_id',
        'creado_por',
        'descripcion',
        'propietario_id',
    ];

    // Carpeta que contiene esta carpeta
    public function carpetaPadre(): BelongsTo
    {
        return $this->belongsTo(
            DocumentoCarpeta::class,
            'carpeta_padre_id'
        );
    }

    // Subcarpetas
    public function subcarpetas(): HasMany
    {
        return $this->hasMany(
            DocumentoCarpeta::class,
            'carpeta_padre_id'
        );
    }

    // Documentos dentro de la carpeta
    public function documentos(): HasMany
    {
        return $this->hasMany(
            Documento::class,
            'carpeta_id'
        );
    }

    // Usuario creador
    public function creador(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'creado_por'
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use SoftDeletes;

    protected $table = 'documentos';

    protected $fillable = [
        'carpeta_id',
        'nombre_original',
        'nombre_almacenado',
        'ruta_archivo',
        'disco',
        'extension',
        'tipo_mime',
        'tamano',
        'descripcion',
        'subido_por',
        'es_publico',
    ];

    protected $casts = [
        'tamano' => 'integer',
        'es_publico' => 'boolean',
    ];

    // Carpeta donde se encuentra
    public function carpeta(): BelongsTo
    {
        return $this->belongsTo(
            DocumentoCarpeta::class,
            'carpeta_id'
        );
    }

    // Usuario que subió el documento
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'subido_por'
        );
    }
}

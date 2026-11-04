<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Jobs\GenerarPreviewDrivePrz;

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

    public function usuariosCompartidos()
    {
        return $this->belongsToMany(
            User::class,
            'documento_usuario',
            'documento_id',
            'user_id'
        )->withTimestamps();
    }


    protected static function booted(): void
    {
        static::created(function (Documento $documento) {

            if (!config('drive-prz.preview_enabled', false)) {
                return;
            }

            $extension = strtolower(pathinfo(
                $documento->nombre_original ?? '',
                PATHINFO_EXTENSION
            ));

            if ($extension !== 'pdf') {
                return;
            }

            // Registrar el estado inicial sin disparar
            // nuevamente eventos del modelo.
            $documento->forceFill([
                'preview_estado' => 'PENDIENTE',
                'preview_error' => null,
                'preview_procesado_at' => null,
            ])->saveQuietly();

            GenerarPreviewDrivePrz::dispatch($documento->id)
                ->delay(now()->addMinutes(2))
                ->afterCommit();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rrhh_documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trabajador_id')
                ->constrained('rrhh_trabajadores')
                ->restrictOnDelete();

            $table->foreignId('tipo_documento_id')
                ->constrained('rrhh_tipos_documentos')
                ->restrictOnDelete();

            $table->string('nombre_original');
            $table->string('nombre_almacenado');
            $table->string('ruta_archivo', 500)->unique();
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('tamano');

            $table->unsignedSmallInteger('anio')->nullable();
            $table->unsignedTinyInteger('mes')->nullable();

            $table->text('observaciones')->nullable();

            $table->foreignId('subido_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'trabajador_id',
                'tipo_documento_id',
                'anio',
                'mes',
            ], 'rrhh_documentos_periodo_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rrhh_documentos');
    }
};
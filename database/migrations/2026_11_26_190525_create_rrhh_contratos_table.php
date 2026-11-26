<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rrhh_contratos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('trabajador_id')
                ->constrained('rrhh_trabajadores')
                ->restrictOnDelete();

            // Información contractual
            $table->string('tipo_contrato', 80)->nullable();
            $table->string('tipo_jornada', 80)->nullable();
            $table->string('detalle_part_time', 100)->nullable();
            $table->string('otra_jornada', 150)->nullable();
            $table->string('jornada_contractual', 150)->nullable();

            // Información organizacional
            $table->string('area', 150)->nullable();
            $table->string('cargo', 255)->nullable();
            $table->string('jefatura_directa', 255)->nullable();

            // Situación laboral
            $table->string('estado_vinculo', 80)->nullable();
            $table->string('situacion_administrativa', 100)->nullable();

            // Fechas contractuales
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_termino')->nullable();
            $table->date('fecha_desvinculacion')->nullable();

            // Información complementaria
            $table->json('ct_extras')->nullable();
            $table->json('hon_extras')->nullable();
            $table->text('observacion')->nullable();

            $table->timestamps();

            // Índices para facilitar las consultas
            $table->index([
                'trabajador_id',
                'fecha_inicio'
            ]);

            $table->index('estado_vinculo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rrhh_contratos');
    }
};
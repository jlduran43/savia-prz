<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rrhh_historial_laboral', function (Blueprint $table) {

            $table->id();

            // Trabajador asociado
            $table->foreignId('trabajador_id')
                ->constrained('rrhh_trabajadores')
                ->restrictOnDelete();

            // Contrato asociado, si corresponde
            $table->foreignId('contrato_id')
                ->nullable()
                ->constrained('rrhh_contratos')
                ->restrictOnDelete();

            // Usuario que realizó el movimiento
            $table->foreignId('registrado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Información del movimiento
            $table->string('tipo_movimiento', 100);
            $table->date('fecha_efectiva');

            // Información anterior y nueva
            $table->json('antes')->nullable();
            $table->json('despues')->nullable();

            // Justificación del movimiento
            $table->text('motivo')->nullable();

            $table->timestamps();

            // Índices
            $table->index([
                'trabajador_id',
                'fecha_efectiva'
            ]);

            $table->index('tipo_movimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rrhh_historial_laboral');
    }
};
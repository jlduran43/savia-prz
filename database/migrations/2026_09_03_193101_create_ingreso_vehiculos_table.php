<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingreso_vehiculos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_movimiento');
            $table->string('centro_ingreso');
            $table->string('responsable');
            $table->string('tipo_vehiculo');
            $table->string('condicion_pago');
            $table->string('medio_pago');

            $table->date('fecha');
            
            $table->time('hora_inicio');
            $table->time('hora_termino');

            $table->decimal('monto', 10, 0);
            
            $table->foreignId('medio_pago_id')
                  ->nullable()
                  ->constrained('medios_pago');
            $table->foreignId('tipo_vehiculo_id')
                ->nullable()
                ->constrained('tipos_vehiculo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_vehiculos');
    }
};

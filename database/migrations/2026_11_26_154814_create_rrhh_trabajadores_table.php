<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rrhh_trabajadores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('rut', 15)->unique();
            $table->string('nombres', 150);
            $table->string('apellido_paterno', 80);
            $table->string('apellido_materno', 80)->nullable();

            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email')->nullable();

            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_desvinculacion')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rrhh_trabajadores');
    }
};
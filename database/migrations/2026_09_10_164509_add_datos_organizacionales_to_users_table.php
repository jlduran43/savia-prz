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
        Schema::table('users', function (Blueprint $table) {

            $table->string('apellido')->nullable();
            $table->string('rut')->nullable()->unique();

            $table->foreignId('departamento_id')
                ->nullable()
                ->constrained('departamentos')
                ->nullOnDelete();

            $table->foreignId('cargo_id')
                ->nullable()
                ->constrained('cargos')
                ->nullOnDelete();

            $table->foreignId('jefe_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('rol')->default('OPERADOR');

            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_carpetas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 255);

            // Permite crear subcarpetas
            $table->foreignId('carpeta_padre_id')
                ->nullable()
                ->constrained('documento_carpetas')
                ->restrictOnDelete();

            // Usuario que creó la carpeta
            $table->foreignId('creado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('descripcion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_carpetas');
    }
};

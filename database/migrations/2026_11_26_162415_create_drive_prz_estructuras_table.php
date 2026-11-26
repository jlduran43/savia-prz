<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_prz_estructuras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nombre', 150);
            $table->longText('estructura');

            $table->timestamps();

            $table->unique(['usuario_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_estructuras');
    }
};
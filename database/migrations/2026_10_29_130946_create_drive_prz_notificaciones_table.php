<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_prz_notificaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('remitente_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('tipo', 50)
                ->default('DOCUMENTO_COMPARTIDO');

            $table->string('elemento_tipo', 20);
            $table->unsignedBigInteger('elemento_id');

            $table->string('elemento_nombre');
            $table->string('mensaje');

            $table->timestamp('leida_at')->nullable();

            $table->timestamps();

            $table->index(['usuario_id', 'leida_at']);
            $table->index(['elemento_tipo', 'elemento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_notificaciones');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_prz_permisos', function (Blueprint $table) {
            $table->id();

            // Elemento compartido
            $table->string('tipo', 10);
            $table->unsignedBigInteger('elemento_id');

            // Usuario destinatario; null para acceso general
            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            // view = Solo ver, edit = Puede editar
            $table->enum('permiso', ['view', 'edit']);

            // Usuario que concedió el permiso
            $table->foreignId('compartido_por')
                ->constrained('users');

            $table->timestamps();

            $table->unique(
                ['tipo', 'elemento_id', 'usuario_id'],
                'drive_permiso_usuario_unique'
            );

            $table->index(['tipo', 'elemento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_permisos');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rrhh_documentos_auditoria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('documento_id')
                ->nullable()
                ->constrained('rrhh_documentos')
                ->nullOnDelete();

            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('accion', 40);
            $table->json('detalle')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rrhh_documentos_auditoria');
    }
};
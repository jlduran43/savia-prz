<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drive_prz_compartidos_ocultos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('tipo', 16);
            $table->unsignedBigInteger('elemento_id');
            $table->timestamps();
            $table->unique(['usuario_id', 'tipo', 'elemento_id'], 'prz_ocultos_unico');
            $table->index(['tipo', 'elemento_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('drive_prz_compartidos_ocultos');
    }
};

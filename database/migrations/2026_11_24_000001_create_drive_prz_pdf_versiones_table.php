<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drive_prz_pdf_versiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('documento_id');
            $table->string('ruta_archivo', 1024);
            $table->unsignedBigInteger('creado_por');
            $table->timestamps();
            $table->index(['documento_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_pdf_versiones');
    }
};

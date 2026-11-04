<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('preview_estado', 20)
                ->nullable()
                ->after('ruta_archivo');

            $table->text('preview_error')
                ->nullable()
                ->after('preview_estado');

            $table->timestamp('preview_procesado_at')
                ->nullable()
                ->after('preview_error');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn([
                'preview_estado',
                'preview_error',
                'preview_procesado_at',
            ]);
        });
    }
};

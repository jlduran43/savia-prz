<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->boolean('es_jefatura_superior')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropColumn('es_jefatura_superior');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rrhh_trabajadores', function (Blueprint $table) {

            $table->string('dependencia', 255)
                ->nullable();

            $table->string('cargo_jefe_inmediato', 255)
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('rrhh_trabajadores', function (Blueprint $table) {

            $table->dropColumn([
                'dependencia',
                'cargo_jefe_inmediato',
            ]);

        });
    }
};
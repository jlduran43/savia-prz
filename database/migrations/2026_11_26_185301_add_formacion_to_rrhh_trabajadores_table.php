<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rrhh_trabajadores', function (Blueprint $table) {
            $table->string('formacion', 255)->nullable();
            $table->text('observacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rrhh_trabajadores', function (Blueprint $table) {
            $table->dropColumn(['formacion', 'observacion']);
        });
    }
};
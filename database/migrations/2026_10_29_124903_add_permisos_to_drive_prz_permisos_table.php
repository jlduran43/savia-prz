<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('drive_prz_permisos', 'permisos')) {
            Schema::table('drive_prz_permisos', function (Blueprint $table) {
                $table->json('permisos')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('drive_prz_permisos', 'permisos')) {
            Schema::table('drive_prz_permisos', function (Blueprint $table) {
                $table->dropColumn('permisos');
            });
        }
    }
};
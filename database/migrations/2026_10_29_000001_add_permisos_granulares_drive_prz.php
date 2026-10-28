<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('drive_prz_permisos', function (Blueprint $table) {
            $table->json('permisos_json')->nullable()->after('permiso');
        });
        // Compatible con ENUM original ('view','edit') en MySQL.
        // Ejecuta después de respaldar la BD y comprobar la estructura.
        DB::statement("ALTER TABLE drive_prz_permisos MODIFY permiso ENUM('view','edit','custom') NOT NULL");
    }
    public function down(): void
    {
        // Convertir personalizados a Solo ver antes de retirar el valor custom.
        DB::table('drive_prz_permisos')->where('permiso', 'custom')->update(['permiso' => 'view']);
        DB::statement("ALTER TABLE drive_prz_permisos MODIFY permiso ENUM('view','edit') NOT NULL");
        Schema::table('drive_prz_permisos', function (Blueprint $table) {
            $table->dropColumn('permisos_json');
        });
    }
};

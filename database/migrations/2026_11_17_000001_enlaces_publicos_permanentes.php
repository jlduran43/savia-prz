<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drive_prz_enlaces', function (Blueprint $table) {
            $table->text('token_cifrado')->nullable();
        });

        Schema::table('drive_prz_enlaces', function (Blueprint $table) {
            $table->dateTime('expira_at')->nullable()->change();
        });

        // Conserva los tokens anteriores, y convierte los no revocados
        // (incluso vencidos) en permanentes.
        DB::table('drive_prz_enlaces')
            ->whereNull('revocado_at')
            ->update(['expira_at' => null]);
    }

    public function down(): void
    {
        // No forzar expiraciones antiguas: una reversión no puede inventar fechas.
        // En producción restaurar desde respaldo si se requiere revertir la política.
        Schema::table('drive_prz_enlaces', function (Blueprint $table) {
            $table->dropColumn('token_cifrado');
        });
    }
};

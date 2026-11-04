
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn(
            'drive_prz_configuraciones',
            'marca_agua_texto_secundario'
        )) {
            Schema::table(
                'drive_prz_configuraciones',
                function (Blueprint $table) {
                    $table->text('marca_agua_texto_secundario')
                        ->nullable();
                }
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn(
            'drive_prz_configuraciones',
            'marca_agua_texto_secundario'
        )) {
            Schema::table(
                'drive_prz_configuraciones',
                function (Blueprint $table) {
                    $table->dropColumn('marca_agua_texto_secundario');
                }
            );
        }
    }
};

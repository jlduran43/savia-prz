<?php

use App\Models\DrivePrzConfiguracion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tabla = (new DrivePrzConfiguracion())->getTable();

        Schema::table($tabla, function (Blueprint $table): void {
            $table->boolean('marca_agua_activa')->default(false);
        });
    }

    public function down(): void
    {
        $tabla = (new DrivePrzConfiguracion())->getTable();

        Schema::table($tabla, function (Blueprint $table): void {
            $table->dropColumn('marca_agua_activa');
        });
    }
};

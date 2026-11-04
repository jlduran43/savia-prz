<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_prz_configuraciones', function (Blueprint $table) {
            $table->id();

            $table->string('marca_agua_texto')
                ->default('DRIVE PRZ - SOLO LECTURA');

            $table->string('marca_agua_color', 7)
                ->default('#14263D');

            $table->unsignedTinyInteger('marca_agua_opacidad')
                ->default(22);

            $table->integer('marca_agua_angulo')
                ->default(-25);

            $table->boolean('usar_tamano_manual')
                ->default(false);

            $table->unsignedSmallInteger('marca_agua_tamano')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_configuraciones');
    }
};
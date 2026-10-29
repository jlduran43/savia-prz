<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_prz_enlaces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('documento_id')->index();

            $table->foreignId('creado_por')->index();

            $table->string('token_hash', 64)->unique();

            $table->timestamp('expira_at')->nullable();

            $table->timestamp('revocado_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_enlaces');
    }
};
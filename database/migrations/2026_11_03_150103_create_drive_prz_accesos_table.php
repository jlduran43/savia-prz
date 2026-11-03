<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_prz_accesos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('documento_id')->nullable();
            $table->unsignedBigInteger('enlace_id')->nullable();

            $table->string('evento', 40);
            $table->unsignedSmallInteger('pagina')->nullable();

            $table->char('cliente_hash', 64)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['documento_id', 'created_at']);
            $table->index(['enlace_id', 'created_at']);
            $table->index(['evento', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_prz_accesos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            // Carpeta a la que pertenece
            $table->foreignId('carpeta_id')
                ->nullable()
                ->constrained('documento_carpetas')
                ->restrictOnDelete();

            // Nombre mostrado al usuario
            $table->string('nombre_original');

            // Nombre físico del archivo
            $table->string('nombre_almacenado');

            // Ubicación privada en el servidor
            $table->string('ruta_archivo', 1024);

            $table->string('disco', 50)
                ->default('documentos_privados');

            $table->string('extension', 20)
                ->nullable();

            $table->string('tipo_mime', 255)
                ->nullable();

            // Tamaño del archivo en bytes
            $table->unsignedBigInteger('tamano')
                ->default(0);

            $table->text('descripcion')->nullable();

            // Usuario que subió el documento
            $table->foreignId('subido_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('nombre_original');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};

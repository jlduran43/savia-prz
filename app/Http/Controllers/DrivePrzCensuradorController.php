<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Primera integración: edición exclusiva de documentos propios.
 * Ajustar la política si más adelante se autoriza edición a usuarios compartidos.
 */
class DrivePrzCensuradorController extends Controller
{
    private function documentoPropio(int $id): Documento
    {
        $documento = Documento::query()->findOrFail($id);
        abort_unless((int) $documento->subido_por === (int) auth()->id(), 403,
            'Solo el propietario puede censurar este PDF.');

        $extension = strtolower(pathinfo((string) $documento->nombre_original, PATHINFO_EXTENSION));
        abort_unless($extension === 'pdf', 415, 'Solo se pueden censurar archivos PDF.');
        abort_unless(!empty($documento->ruta_archivo), 404);
        abort_unless(Storage::disk('documentos_privados')->exists($documento->ruta_archivo), 404);

        return $documento;
    }

    public function screen(int $id): Response
    {
        $this->documentoPropio($id);
        $path = resource_path('drive-prz/censurador.html');
        abort_unless(is_file($path), 404);

        $html = file_get_contents($path);
        abort_if($html === false, 500);
        // El formulario usa CSRF normal de Laravel (no se desactiva la protección).
        $csrf = '<meta name="csrf-token" content="' . e(csrf_token()) . '">';
        $html = str_replace('</head>', $csrf . '</head>', $html);

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function save(Request $request, int $id): JsonResponse
    {
        $documento = $this->documentoPropio($id);
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf', 'max:30720'], // 30 MB
        ]);

        $archivo = $request->file('archivo');
        $stream = fopen($archivo->getRealPath(), 'rb');
        abort_if($stream === false, 422, 'No se pudo leer el PDF.');
        try {
            abort_unless(fread($stream, 5) === '%PDF-', 422, 'El contenido no es un PDF válido.');
        } finally {
            fclose($stream);
        }

        $disco = Storage::disk('documentos_privados');
        $actual = $documento->ruta_archivo;
        $carpeta = trim(dirname($actual), '.\\/');
        $nuevo = ($carpeta ? $carpeta . '/' : '') . 'censurados/' . $id . '-' . Str::uuid() . '.pdf';

        // No sobreescribimos el original: queda privado como respaldo recuperable.
        // Se crea la nueva versión antes de cambiar la referencia de BD.
        $input = fopen($archivo->getRealPath(), 'rb');
        abort_if($input === false, 500, 'No fue posible preparar el archivo.');
        try {
            $ok = $disco->put($nuevo, $input);
        } finally {
            fclose($input);
        }
        abort_unless($ok, 500, 'No se pudo almacenar el PDF censurado.');

        try {
            // Revalidar la propiedad justo antes de actualizar para evitar sobrescribir
            // un cambio de propietario o una versión concurrente.
            $updated = Documento::query()
                ->whereKey($id)
                ->where('subido_por', auth()->id())
                ->where('ruta_archivo', $actual)
                ->update(['ruta_archivo' => $nuevo]);
            if ($updated !== 1) {
                $disco->delete($nuevo);
                return response()->json([
                    'message' => 'El documento cambió mientras lo editabas. Vuelve a abrirlo antes de guardar.',
                ], 409);
            }
        } catch (\Throwable $e) {
            $disco->delete($nuevo);
            throw $e;
        }

        return response()->json([
            'ok' => true,
            'message' => 'PDF censurado guardado en Drive PRZ.',
        ]);
    }
}

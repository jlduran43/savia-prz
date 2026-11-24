<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DrivePrzCensuradorController extends Controller
{
    private const DISK = 'documentos_privados';
    private const TABLA_VERSIONES = 'drive_prz_pdf_versiones';

    private function documentoPropio(int $id): Documento
    {
        $documento = Documento::query()->findOrFail($id);
        abort_unless(
            (int) $documento->subido_por === (int) auth()->id(),
            403,
            'Solo el propietario puede administrar versiones de este PDF.'
        );
        $extension = strtolower(pathinfo((string) $documento->nombre_original, PATHINFO_EXTENSION));
        abort_unless($extension === 'pdf', 415, 'Solo se pueden editar archivos PDF.');
        abort_unless(!empty($documento->ruta_archivo), 404);
        abort_unless(Storage::disk(self::DISK)->exists($documento->ruta_archivo), 404);
        return $documento;
    }

    public function screen(int $id): Response
    {
        $this->documentoPropio($id);
        $path = resource_path('drive-prz/censurador.html');
        abort_unless(is_file($path), 404);
        $html = file_get_contents($path);
        abort_if($html === false, 500);
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
        $request->validate(['archivo' => ['required', 'file', 'mimes:pdf', 'max:30720']]);
        $archivo = $request->file('archivo');
        $stream = fopen($archivo->getRealPath(), 'rb');
        abort_if($stream === false, 422, 'No se pudo leer el PDF.');
        try {
            abort_unless(fread($stream, 5) === '%PDF-', 422, 'El contenido no tiene cabecera PDF.');
        } finally {
            fclose($stream);
        }

        $disco = Storage::disk(self::DISK);
        $anterior = $documento->ruta_archivo;
        $carpeta = trim(dirname($anterior), '.\\/');
        $nuevo = ($carpeta ? $carpeta . '/' : '') . 'censurados/' . $id . '-' . Str::uuid() . '.pdf';
        $input = fopen($archivo->getRealPath(), 'rb');
        abort_if($input === false, 500, 'No fue posible preparar el PDF.');
        try {
            $ok = $disco->put($nuevo, $input);
        } finally {
            fclose($input);
        }
        abort_unless($ok, 500, 'No se pudo almacenar el PDF.');

        try {
            DB::transaction(function () use ($id, $anterior, $nuevo, $disco): void {
                $doc = Documento::query()->whereKey($id)->lockForUpdate()->firstOrFail();
                abort_unless((int) $doc->subido_por === (int) auth()->id(), 403);
                abort_unless(
                    $doc->ruta_archivo === $anterior,
                    409,
                    'El documento cambió mientras lo editabas. Vuelve a abrirlo.'
                );
                abort_unless($disco->exists($anterior), 404, 'La versión anterior ya no existe.');

                // Guardar referencia a la versión previa, que permanece en el disco privado.
                DB::table(self::TABLA_VERSIONES)->insert([
                    'documento_id' => $id,
                    'ruta_archivo' => $anterior,
                    'creado_por' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $doc->ruta_archivo = $nuevo;
                $doc->save();
            });
        } catch (\Throwable $e) {
            $disco->delete($nuevo);
            throw $e;
        }
        return response()->json(['ok' => true, 'message' => 'PDF guardado; versión anterior disponible.']);
    }

    public function versions(int $id): JsonResponse
    {
        $this->documentoPropio($id);
        $disco = Storage::disk(self::DISK);
        $filas = DB::table(self::TABLA_VERSIONES)
            ->where('documento_id', $id)->orderByDesc('id')
            ->get(['id', 'ruta_archivo', 'created_at']);
        return response()->json([
            'ok' => true,
            'versions' => $filas->map(fn($v) => [
                'id' => $v->id,
                'created_at' => $v->created_at,
                'available' => $disco->exists($v->ruta_archivo),
            ])->values(),
        ]);
    }

    public function restore(Request $request, int $id, int $version): JsonResponse
    {
        $this->documentoPropio($id);
        $disco = Storage::disk(self::DISK);
        DB::transaction(function () use ($id, $version, $disco): void {
            $doc = Documento::query()->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless((int) $doc->subido_por === (int) auth()->id(), 403);
            $v = DB::table(self::TABLA_VERSIONES)
                ->where('documento_id', $id)->where('id', $version)->first();
            abort_unless($v, 404, 'Versión no encontrada.');
            abort_unless($disco->exists($v->ruta_archivo), 404, 'El PDF de esa versión ya no está disponible.');
            abort_unless($disco->exists($doc->ruta_archivo), 404, 'El PDF actual no está disponible.');
            abort_unless($doc->ruta_archivo !== $v->ruta_archivo, 409, 'La versión ya está activa.');

            // La versión reemplazada también queda recuperable.
            DB::table(self::TABLA_VERSIONES)->insert([
                'documento_id' => $id,
                'ruta_archivo' => $doc->ruta_archivo,
                'creado_por' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $doc->ruta_archivo = $v->ruta_archivo;
            $doc->save();
            DB::table(self::TABLA_VERSIONES)->where('id', $version)->where('documento_id', $id)->delete();
        });
        return response()->json(['ok' => true, 'message' => 'Versión anterior restaurada en Drive PRZ.']);
    }

    public function previewVersion(
        int $id,
        int $versionId
    ): Response {
        $this->documentoPropio($id);

        $version = \Illuminate\Support\Facades\DB::table(
            'drive_prz_pdf_versiones'
        )
            ->where('documento_id', $id)
            ->where('id', $versionId)
            ->first();

        abort_unless($version, 404);

        $disk = Storage::disk('documentos_privados');

        abort_unless(
            $disk->exists($version->ruta_archivo),
            404
        );

        return $disk->response(
            $version->ruta_archivo,
            'version-' . $versionId . '.pdf',
            [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }
}

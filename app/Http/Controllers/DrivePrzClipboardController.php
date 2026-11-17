<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Copia archivos/carpetas propios dentro de Mis archivos.
 * El servidor valida propiedad, destino y jerarquía independientemente del navegador.
 */
class DrivePrzClipboardController extends Controller
{
    private array $createdPaths = [];
    private int $visited = 0;
    private const MAX_ITEMS = 500;
    private const MAX_DEPTH = 30;

    public function paste(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:file,folder'],
            'id' => ['required', 'integer', 'min:1'],
            'carpeta_destino_id' => ['nullable', 'integer', 'min:1'],
        ]);

        $userId = (int) $request->user()->id;
        $destination = $data['carpeta_destino_id'] ?? null;
        $this->createdPaths = [];
        $this->visited = 0;

        try {
            $result = DB::transaction(function () use ($data, $userId, $destination) {
                if ($destination !== null) {
                    $this->ownedFolder((int) $destination, $userId);
                }
                if ($data['tipo'] === 'file') {
                    $original = $this->ownedFile((int) $data['id'], $userId);
                    $copyId = $this->copyFile($original, $destination, $userId);
                } else {
                    $original = $this->ownedFolder((int) $data['id'], $userId);
                    $copyId = $this->copyFolder($original, $destination, $userId, 0);
                }
                return $copyId;
            }, 1);
        } catch (Throwable $e) {
            foreach ($this->createdPaths as $path) {
                Storage::disk('documentos_privados')->delete($path);
            }
            throw $e;
        }

        return response()->json([
            'message' => 'Copia creada correctamente.',
            'id' => $result,
            'tipo' => $data['tipo'],
        ], 201);
    }

    private function ownedFolder(int $id, int $userId): object
    {
        $row = DB::table('documento_carpetas')
            ->where('id', $id)->where('creado_por', $userId)
            ->whereNull('deleted_at')->first();
        abort_unless($row, 403, 'No tienes permiso para copiar o usar esta carpeta.');
        return $row;
    }

    private function ownedFile(int $id, int $userId): object
    {
        $row = DB::table('documentos')
            ->where('id', $id)->where('subido_por', $userId)
            ->whereNull('deleted_at')->first();
        abort_unless($row, 403, 'No tienes permiso para copiar este archivo.');
        return $row;
    }

    private function countItem(int $depth): void
    {
        if (++$this->visited > self::MAX_ITEMS || $depth > self::MAX_DEPTH) {
            throw ValidationException::withMessages([
                'id' => 'La carpeta contiene demasiados elementos o niveles para esta operación.',
            ]);
        }
    }

    private function uniqueName(string $table, string $column, ?int $parent, string $name, bool $file): string
    {
        $name = trim($name);
        $extension = $file ? pathinfo($name, PATHINFO_EXTENSION) : '';
        $stem = $file && $extension !== '' ? mb_substr($name, 0, -(mb_strlen($extension) + 1)) : $name;
        $tail = $file && $extension !== '' ? '.' . $extension : '';
        $parentColumn = $file ? 'carpeta_id' : 'carpeta_padre_id';
        $exists = function (string $candidate) use ($table, $column, $parent, $parentColumn): bool {
            return DB::table($table)->whereNull('deleted_at')
                ->where($parentColumn, $parent)->where($column, $candidate)->exists();
        };
        $candidate = $name;
        $n = 1;
        while ($exists($candidate)) {
            $suffix = $n === 1 ? ' (copia)' : " (copia {$n})";
            $candidate = mb_substr($stem, 0, max(1, 180 - mb_strlen($suffix . $tail))) . $suffix . $tail;
            if (++$n > 10000) throw new \RuntimeException('No se pudo reservar un nombre disponible.');
        }
        return $candidate;
    }

    private function copyFile(object $original, ?int $destination, int $userId): int
    {
        $this->countItem(0);
        $source = (string) $original->ruta_archivo;
        $disk = Storage::disk('documentos_privados');
        if (!$disk->exists($source)) {
            throw ValidationException::withMessages(['id' => 'El archivo original no existe en el almacenamiento.']);
        }
        $ext = pathinfo($source, PATHINFO_EXTENSION);
        $newPath = 'drive-prz-copias/' . Str::uuid() . ($ext !== '' ? '.' . $ext : '');
        if (!$disk->copy($source, $newPath)) throw new \RuntimeException('No se pudo copiar el archivo físico.');
        $this->createdPaths[] = $newPath;

        $row = (array) $original;
        unset($row['id']);
        $row['carpeta_id'] = $destination;
        $row['subido_por'] = $userId;
        $row['ruta_archivo'] = $newPath;
        $name = (string) ($original->nombre_original ?: $original->nombre);
        $row['nombre_original'] = $this->uniqueName('documentos', 'nombre_original', $destination, $name, true);
        // En esta base, nombre puede ser nombre físico o visible. Conservamos su formato.
        if (array_key_exists('nombre', $row) && $row['nombre'] === $name) {
            $row['nombre'] = $row['nombre_original'];
        }
        if (array_key_exists('created_at', $row)) $row['created_at'] = now();
        if (array_key_exists('updated_at', $row)) $row['updated_at'] = now();
        if (array_key_exists('deleted_at', $row)) $row['deleted_at'] = null;
        return (int) DB::table('documentos')->insertGetId($row);
    }

    private function copyFolder(object $original, ?int $destination, int $userId, int $depth): int
    {
        $this->countItem($depth);
        $row = (array) $original;
        unset($row['id']);
        $row['nombre'] = $this->uniqueName('documento_carpetas', 'nombre', $destination, (string) $original->nombre, false);
        $row['carpeta_padre_id'] = $destination;
        $row['creado_por'] = $userId;
        if (array_key_exists('created_at', $row)) $row['created_at'] = now();
        if (array_key_exists('updated_at', $row)) $row['updated_at'] = now();
        if (array_key_exists('deleted_at', $row)) $row['deleted_at'] = null;
        $newId = (int) DB::table('documento_carpetas')->insertGetId($row);

        $files = DB::table('documentos')->where('carpeta_id', $original->id)
            ->whereNull('deleted_at')->get();
        foreach ($files as $file) {
            // Evita copiar archivos ajenos incorporados a una carpeta propia.
            abort_unless((int) $file->subido_por === $userId, 403, 'La carpeta contiene archivos sin permiso de copia.');
            $this->copyFile($file, $newId, $userId);
        }
        $folders = DB::table('documento_carpetas')->where('carpeta_padre_id', $original->id)
            ->whereNull('deleted_at')->get();
        foreach ($folders as $folder) {
            abort_unless((int) $folder->creado_por === $userId, 403, 'La carpeta contiene subcarpetas sin permiso de copia.');
            $this->copyFolder($folder, $newId, $userId, $depth + 1);
        }
        return $newId;
    }
}

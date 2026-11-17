<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class DrivePrzClipboardController extends Controller
{
    public function paste(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:file,folder'],
            'id' => ['required', 'integer', 'min:1'],
            'carpeta_destino_id' => ['present', 'nullable', 'integer', 'min:1'],
        ]);

        $userId = (int) auth()->id();
        $destinationId = $data['carpeta_destino_id'] === null
            ? null : (int) $data['carpeta_destino_id'];
        $sourceId = (int) $data['id'];
        $disk = Storage::disk('documentos_privados');
        $createdPaths = [];

        try {
            $result = DB::transaction(function () use (
                $data, $userId, $destinationId, $sourceId, $disk, &$createdPaths
            ) {
                if ($destinationId !== null) {
                    $this->ownedFolder($destinationId, $userId);
                }

                if ($data['tipo'] === 'file') {
                    $source = $this->ownedFile($sourceId, $userId);
                    $copy = $this->copyFile(
                        $source, $destinationId, $userId, $disk, $createdPaths
                    );
                    return ['type' => 'file', 'id' => $copy->id];
                }

                $source = $this->ownedFolder($sourceId, $userId);
                $this->validateFolderDestination($source, $destinationId, $userId);

                $count = 0;
                $newFolder = $this->copyFolderTree(
                    $source, $destinationId, $userId, $disk,
                    $createdPaths, $count, 0
                );
                return ['type' => 'folder', 'id' => $newFolder->id];
            });

            return response()->json([
                'ok' => true,
                'message' => 'Copia creada correctamente.',
                'elemento' => $result,
            ], 201);
        } catch (Throwable $e) {
            // Si falló la transacción, retirar los archivos físicos nuevos.
            foreach ($createdPaths as $path) {
                try {
                    $disk->delete($path);
                } catch (Throwable $cleanupError) {
                    report($cleanupError);
                }
            }
            throw $e;
        }
    }

    private function ownedFolder(int $id, int $userId): DocumentoCarpeta
    {
        return DocumentoCarpeta::query()
            ->where('creado_por', $userId)
            ->whereNull('deleted_at')
            ->findOrFail($id);
    }

    private function ownedFile(int $id, int $userId): Documento
    {
        return Documento::query()
            ->where('subido_por', $userId)
            ->whereNull('deleted_at')
            ->findOrFail($id);
    }

    private function validateFolderDestination(
        DocumentoCarpeta $source, ?int $destinationId, int $userId
    ): void {
        $cursor = $destinationId;
        $visited = [];
        while ($cursor !== null) {
            if ($cursor === (int) $source->id) {
                throw ValidationException::withMessages([
                    'carpeta_destino_id' =>
                        'No puedes copiar una carpeta dentro de sí misma o de sus subcarpetas.',
                ]);
            }
            if (isset($visited[$cursor])) {
                throw ValidationException::withMessages([
                    'carpeta_destino_id' => 'Se detectó una estructura circular de carpetas.',
                ]);
            }
            $visited[$cursor] = true;
            $folder = $this->ownedFolder($cursor, $userId);
            $cursor = $folder->carpeta_padre_id === null
                ? null : (int) $folder->carpeta_padre_id;
        }
    }

    private function copyFolderTree(
        DocumentoCarpeta $source,
        ?int $destinationId,
        int $userId,
        $disk,
        array &$createdPaths,
        int &$count,
        int $depth
    ): DocumentoCarpeta {
        if (++$count > 500 || $depth > 15) {
            throw ValidationException::withMessages([
                'id' => 'La carpeta supera el límite de 500 elementos o 15 niveles por copia.',
            ]);
        }

        $copy = $source->replicate(['id', 'created_at', 'updated_at', 'deleted_at']);
        $copy->nombre = $this->uniqueFolderName($source->nombre, $destinationId, $userId);
        $copy->carpeta_padre_id = $destinationId;
        $copy->creado_por = $userId;
        $copy->deleted_at = null;
        $copy->save();

        // Consultamos únicamente hijos del origen, no los de la carpeta recién creada.
        $files = Documento::query()
            ->where('subido_por', $userId)
            ->where('carpeta_id', $source->id)
            ->whereNull('deleted_at')
            ->orderBy('id')->get();

        foreach ($files as $file) {
            if (++$count > 500) {
                throw ValidationException::withMessages([
                    'id' => 'La carpeta supera el límite de 500 elementos por copia.',
                ]);
            }
            $this->copyFile($file, (int) $copy->id, $userId, $disk, $createdPaths);
        }

        $children = DocumentoCarpeta::query()
            ->where('creado_por', $userId)
            ->where('carpeta_padre_id', $source->id)
            ->whereNull('deleted_at')
            ->orderBy('id')->get();

        foreach ($children as $child) {
            $this->copyFolderTree(
                $child, (int) $copy->id, $userId, $disk,
                $createdPaths, $count, $depth + 1
            );
        }

        return $copy;
    }

    private function copyFile(
        Documento $source,
        ?int $destinationId,
        int $userId,
        $disk,
        array &$createdPaths
    ): Documento {
        $sourcePath = $source->ruta_archivo;
        if (!$sourcePath || !$disk->exists($sourcePath)) {
            throw ValidationException::withMessages([
                'id' => 'El archivo original no existe en el almacenamiento privado.',
            ]);
        }

        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $newPath = 'archivos/' . Str::uuid()
            . ($extension !== '' ? '.' . $extension : '');

        if (!$disk->copy($sourcePath, $newPath)) {
            throw new RuntimeException('No fue posible copiar el archivo físico.');
        }
        $createdPaths[] = $newPath;

        $copy = $source->replicate(['id', 'created_at', 'updated_at', 'deleted_at']);
        $copy->carpeta_id = $destinationId;
        $copy->nombre_original = $this->uniqueFileName(
            $source->nombre_original, $destinationId, $userId
        );
        $copy->nombre_almacenado = basename($newPath);
        $copy->ruta_archivo = $newPath;
        $copy->subido_por = $userId;
        $copy->deleted_at = null;
        $copy->save();

        return $copy;
    }

    private function uniqueFolderName(string $original, ?int $parentId, int $userId): string
    {
        $used = DocumentoCarpeta::query()
            ->where('creado_por', $userId)
            ->where('carpeta_padre_id', $parentId)
            ->whereNull('deleted_at')->pluck('nombre')
            ->map(fn ($name) => mb_strtolower(trim($name)))->all();

        return $this->availableName(trim($original), $used, 120);
    }

    private function uniqueFileName(string $original, ?int $parentId, int $userId): string
    {
        $used = Documento::query()
            ->where('subido_por', $userId)
            ->where('carpeta_id', $parentId)
            ->whereNull('deleted_at')->pluck('nombre_original')
            ->map(fn ($name) => mb_strtolower(trim($name)))->all();

        return $this->availableName(trim($original), $used, 180, true);
    }

    private function availableName(
        string $original, array $used, int $maxLength, bool $isFile = false
    ): string {
        if (!in_array(mb_strtolower($original), $used, true)) {
            return $original;
        }

        $extension = '';
        $base = $original;
        if ($isFile) {
            $pos = mb_strrpos($original, '.');
            if ($pos !== false && $pos > 0) {
                $base = mb_substr($original, 0, $pos);
                $extension = mb_substr($original, $pos);
            }
        }

        for ($i = 1; $i <= 10000; $i++) {
            $suffix = $i === 1 ? ' (copia)' : ' (copia ' . $i . ')';
            $maxBase = $maxLength - mb_strlen($suffix) - mb_strlen($extension);
            if ($maxBase < 1) {
                throw ValidationException::withMessages(['nombre' => 'El nombre es demasiado largo.']);
            }
            $candidate = rtrim(mb_substr($base, 0, $maxBase)) . $suffix . $extension;
            if (!in_array(mb_strtolower($candidate), $used, true)) {
                return $candidate;
            }
        }

        throw ValidationException::withMessages([
            'nombre' => 'No fue posible generar un nombre de copia único.',
        ]);
    }
}

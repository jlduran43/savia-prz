<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/** Documentos compartidos: acceso por permisos efectivos, sin alterar Mis archivos. */
class DrivePrzSharedController extends Controller
{
    private const TABLE = 'drive_prz_permisos';
    private const PERMISSIONS = ['ver', 'descargar', 'renombrar', 'mover', 'copiar', 'eliminar', 'compartir'];

    private function types(string $kind): array
    {
        return $kind === 'folders'
            ? ['folders', 'folder', 'carpeta', 'documento_carpeta', 'App\\Models\\DocumentoCarpeta']
            : ['files', 'file', 'archivo', 'documento', 'App\\Models\\Documento'];
    }

    private function fromGrant(object $grant): array
    {
        // El controlador de Compartir escribe el JSON en la columna `permisos`.
        foreach (['permisos', 'permisos_json'] as $column) {
            if (isset($grant->{$column}) && $grant->{$column} !== '') {
                $decoded = json_decode((string) $grant->{$column}, true);
                if (is_array($decoded)) {
                    return array_values(array_intersect(self::PERMISSIONS, $decoded));
                }
            }
        }
        // Compatibilidad con registros anteriores a los permisos individuales.
        return match (strtolower((string) ($grant->permiso ?? ''))) {
            'view', 'ver' => ['ver', 'descargar'],
            'edit', 'editar' => ['ver', 'descargar', 'renombrar', 'mover', 'copiar'],
            default => [],
        };
    }

    private function directPermissions(string $kind, int $id): array
    {
        $grants = DB::table(self::TABLE)
            ->whereIn('tipo', $this->types($kind))
            ->where('elemento_id', $id)
            ->where(function ($q) {
                $q->where('usuario_id', auth()->id())->orWhereNull('usuario_id');
            })->get();
        $permissions = [];
        foreach ($grants as $grant) {
            $permissions = array_merge($permissions, $this->fromGrant($grant));
        }
        return array_values(array_unique($permissions));
    }

    private function folderPermissions(int $id): array
    {
        $seen = [];
        $permissions = [];
        while ($id > 0) {
            abort_if(isset($seen[$id]), 409, 'Estructura circular de carpetas.');
            $seen[$id] = true;
            $folder = DB::table('documento_carpetas')->where('id', $id)
                ->whereNull('deleted_at')->first();
            if (!$folder) return [];
            $permissions = array_merge($permissions, $this->directPermissions('folders', $id));
            $id = (int) ($folder->carpeta_padre_id ?? 0);
        }
        return array_values(array_unique($permissions));
    }

    private function effectiveFilePermissions(object $file): array
    {
        if ($file->carpeta_id !== null) {
            $inherited = $this->folderPermissions((int) $file->carpeta_id);
            // Si falta algún ancestro, no exponer el documento.
            $this->assertFolderTreeValid((int) $file->carpeta_id);
        } else {
            $inherited = [];
        }
        return array_values(array_unique(array_merge(
            $this->directPermissions('files', (int) $file->id),
            $inherited
        )));
    }

    private function assertFolderTreeValid(int $id): void
    {
        $seen = [];
        while ($id > 0) {
            abort_if(isset($seen[$id]), 403);
            $seen[$id] = true;
            $folder = DB::table('documento_carpetas')->where('id', $id)
                ->whereNull('deleted_at')->first();
            abort_unless($folder, 404);
            $id = (int) ($folder->carpeta_padre_id ?? 0);
        }
    }

    private function accessibleFile(int $id, string $permission = 'ver'): object
    {
        $file = DB::table('documentos')->where('id', $id)->whereNull('deleted_at')->firstOrFail();
        abort_unless((int) $file->subido_por !== (int) auth()->id(), 403, 'Utiliza Mis archivos.');
        abort_unless(in_array($permission, $this->effectiveFilePermissions($file), true),
            403, 'No tienes permiso para realizar esta acción.');
        return $file;
    }

    private function ownerName(int $uid): string
    {
        $user = DB::table('users')->where('id', $uid)->first();
        return trim(($user->nombre ?? $user->name ?? '') . ' ' . ($user->apellido ?? '')) ?: 'Otro usuario';
    }

    private function fileData(object $file): array
    {
        $disk = Storage::disk('documentos_privados');
        $permissions = $this->effectiveFilePermissions($file);
        return [
            'id' => $file->id,
            'type' => 'file',
            'name' => $file->nombre_original,
            'modified' => $file->updated_at,
            'parent' => $file->carpeta_id,
            'owner_id' => $file->subido_por,
            'owner' => $this->ownerName((int) $file->subido_por),
            'size' => $disk->exists($file->ruta_archivo) ? $disk->size($file->ruta_archivo) : 0,
            'permissions' => $permissions,
            'permisos' => $permissions,
            'role' => count(array_intersect($permissions, ['renombrar', 'mover', 'copiar', 'eliminar', 'compartir'])) ? 'edit' : 'view',
        ];
    }

    private function folderData(object $folder): array
    {
        $permissions = $this->folderPermissions((int) $folder->id);
        return [
            'id' => $folder->id,
            'type' => 'folder',
            'name' => $folder->nombre,
            'modified' => $folder->updated_at,
            'parent' => $folder->carpeta_padre_id,
            'owner_id' => $folder->creado_por,
            'owner' => $this->ownerName((int) $folder->creado_por),
            'permissions' => $permissions,
            'permisos' => $permissions,
            'role' => count(array_intersect($permissions, ['renombrar', 'mover', 'copiar', 'eliminar', 'compartir'])) ? 'edit' : 'view',
        ];
    }

    public function listing(Request $request)
    {
        $data = $request->validate(['carpeta_id' => ['nullable', 'integer', 'min:1']]);
        $parent = isset($data['carpeta_id']) ? (int) $data['carpeta_id'] : null;
        $uid = (int) auth()->id();
        $items = [];
        $trail = [];
        if ($parent !== null) {
            abort_unless(in_array('ver', $this->folderPermissions($parent), true), 403,
                'No tienes acceso a esta carpeta.');
            $cursor = $parent;
            $seen = [];
            while ($cursor && !isset($seen[$cursor])) {
                $seen[$cursor] = true;
                $folder = DB::table('documento_carpetas')->where('id', $cursor)
                    ->whereNull('deleted_at')->first();
                abort_unless($folder, 404);
                array_unshift($trail, ['id' => $folder->id, 'name' => $folder->nombre]);
                $cursor = $folder->carpeta_padre_id;
            }
            abort_if($cursor, 409, 'Estructura de carpetas circular.');
            $folders = DB::table('documento_carpetas')->where('carpeta_padre_id', $parent)
                ->whereNull('deleted_at')->orderBy('nombre')->get();
            $files = DB::table('documentos')->where('carpeta_id', $parent)
                ->whereNull('deleted_at')->orderBy('nombre_original')->get();
            foreach ($folders as $folder) {
                if ((int) $folder->creado_por !== $uid && in_array('ver', $this->folderPermissions((int) $folder->id), true)) {
                    $items[] = $this->folderData($folder);
                }
            }
            foreach ($files as $file) {
                if ((int) $file->subido_por !== $uid && in_array('ver', $this->effectiveFilePermissions($file), true)) {
                    $items[] = $this->fileData($file);
                }
            }
        } else {
            $grants = DB::table(self::TABLE)->where('usuario_id', $uid)->get();
            $found = [];
            foreach ($grants as $grant) {
                $type = strtolower((string) $grant->tipo);
                $id = (int) $grant->elemento_id;
                if (in_array($type, array_map('strtolower', $this->types('folders')), true)) {
                    $folder = DB::table('documento_carpetas')->where('id', $id)->whereNull('deleted_at')->first();
                    if ($folder && (int) $folder->creado_por !== $uid && !isset($found['folder:'.$id])
                        && in_array('ver', $this->folderPermissions($id), true)) {
                        $items[] = $this->folderData($folder);
                        $found['folder:'.$id] = true;
                    }
                } elseif (in_array($type, array_map('strtolower', $this->types('files')), true)) {
                    $file = DB::table('documentos')->where('id', $id)->whereNull('deleted_at')->first();
                    if ($file && (int) $file->subido_por !== $uid && !isset($found['file:'.$id])
                        && in_array('ver', $this->effectiveFilePermissions($file), true)) {
                        $items[] = $this->fileData($file);
                        $found['file:'.$id] = true;
                    }
                }
            }
        }
        return response()->json(['items' => $items, 'trail' => $trail]);
    }

    public function download(int $id)
    {
        $file = $this->accessibleFile($id, 'descargar');
        $disk = Storage::disk('documentos_privados');
        abort_unless($disk->exists($file->ruta_archivo), 404);
        return $disk->download($file->ruta_archivo, $file->nombre_original);
    }

    public function preview(int $id): BinaryFileResponse
    {
        $file = $this->accessibleFile($id, 'ver');
        $disk = Storage::disk('documentos_privados');
        abort_unless($disk->exists($file->ruta_archivo), 404);
        $mime = $disk->mimeType($file->ruta_archivo);
        abort_unless(in_array($mime, ['application/pdf', 'image/png', 'image/jpeg'], true),
            415, 'Formato sin vista previa; descárgalo.');
        return response()->file($disk->path($file->ruta_archivo), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function rename(Request $request, int $id)
    {
        $data = $request->validate(['nombre' => ['required', 'string', 'max:120']]);
        $file = $this->accessibleFile($id, 'renombrar');
        DB::table('documentos')->where('id', $file->id)->whereNull('deleted_at')->update([
            'nombre_original' => trim($data['nombre']), 'updated_at' => now(),
        ]);
        return response()->json(['ok' => true]);
    }

    public function move(Request $request, int $id)
    {
        $data = $request->validate(['destino_id' => ['required', 'integer', 'min:1']]);
        $file = $this->accessibleFile($id, 'mover');
        $dest = DB::table('documento_carpetas')->where('id', $data['destino_id'])
            ->where('creado_por', $file->subido_por)->whereNull('deleted_at')->first();
        abort_unless($dest, 403, 'El destino debe pertenecer al propietario del documento.');
        $this->assertFolderTreeValid((int) $dest->id);
        // La carpeta de destino debe conceder al destinatario permiso de mover.
        abort_unless(in_array('mover', $this->folderPermissions((int) $dest->id), true),
            403, 'No tienes permiso para mover documentos a esta carpeta.');
        abort_if((int) $file->carpeta_id === (int) $dest->id, 422,
            'El documento ya está en esta carpeta.');
        DB::table('documentos')->where('id', $file->id)->whereNull('deleted_at')->update([
            'carpeta_id' => $dest->id, 'updated_at' => now(),
        ]);
        return response()->json(['ok' => true]);
    }

    public function destinations()
    {
        $folders = DB::table('documento_carpetas')->whereNull('deleted_at')
            ->orderBy('nombre')->get(['id', 'nombre', 'carpeta_padre_id', 'creado_por'])
            ->filter(fn ($folder) => in_array('mover', $this->folderPermissions((int) $folder->id), true))
            ->values();
        return response()->json(['folders' => $folders]);
    }

    public function copy(Request $request, int $id)
    {
        $data = $request->validate(['destino_id' => ['present', 'nullable', 'integer', 'min:1']]);
        $file = $this->accessibleFile($id, 'copiar');
        $destination = $data['destino_id'] === null ? null : (int) $data['destino_id'];
        if ($destination !== null) {
            $folder = DB::table('documento_carpetas')->where('id', $destination)
                ->where('creado_por', auth()->id())->whereNull('deleted_at')->first();
            abort_unless($folder, 403, 'Solo puedes copiar a una carpeta propia.');
            $this->assertFolderTreeValid($destination);
        }
        $disk = Storage::disk('documentos_privados');
        abort_unless($disk->exists($file->ruta_archivo), 404);
        $newPath = 'archivos/' . \Illuminate\Support\Str::random(40);
        abort_unless($disk->copy($file->ruta_archivo, $newPath), 500, 'No se pudo copiar el archivo.');
        try {
            $newId = DB::table('documentos')->insertGetId([
                'carpeta_id' => $destination,
                'nombre_original' => $file->nombre_original,
                'nombre_almacenado' => basename($newPath),
                'ruta_archivo' => $newPath,
                'subido_por' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $error) {
            $disk->delete($newPath);
            throw $error;
        }
        return response()->json(['ok' => true, 'id' => $newId], 201);
    }
}

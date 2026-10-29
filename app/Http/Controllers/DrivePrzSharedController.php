<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/** Compartidos conmigo. No altera el controlador de documentos propios. */
class DrivePrzSharedController extends Controller
{
    private const PERMISOS = 'drive_prz_permisos';

    private function config(): array
    {
        abort_unless(Schema::hasTable(self::PERMISOS), 503, 'La tabla de permisos no existe.');
        $cols = Schema::getColumnListing(self::PERMISOS);
        $pick = static function (array $names) use ($cols): ?string {
            foreach ($names as $name) if (in_array($name, $cols, true)) return $name;
            return null;
        };
        $tipo = $pick(['elemento_tipo', 'tipo_elemento', 'recurso_tipo']);
        $id = $pick(['elemento_id', 'recurso_id']);
        $destinatario = $pick(['usuario_id', 'destinatario_id', 'usuario_destino_id', 'compartido_con_id']);
        abort_unless($tipo && $id && $destinatario, 503,
            'No se pudo interpretar drive_prz_permisos. Revisa los nombres de columnas con SHOW COLUMNS FROM drive_prz_permisos.');
        return compact('tipo', 'id', 'destinatario');
    }

    private function permisos(string $kind, int $id): bool
    {
        $cfg = $this->config();
        $types = $kind === 'folders' ? ['folders','folder','carpeta','documento_carpeta','App\\Models\\DocumentoCarpeta']
          : ['files','file','archivo','documento','App\\Models\\Documento'];
        return DB::table(self::PERMISOS)
            ->where($cfg['destinatario'], auth()->id())
            ->whereIn($cfg['tipo'], $types)
            ->where($cfg['id'], $id)
            ->when(Schema::hasColumn(self::PERMISOS, 'deleted_at'), fn($q) => $q->whereNull('deleted_at'))
            ->exists();
    }

    private function canFolder(int $id): bool
    {
        $seen = [];
        while ($id > 0 && !isset($seen[$id])) {
            $seen[$id] = true;
            $folder = DB::table('documento_carpetas')->where('id', $id)->whereNull('deleted_at')->first();
            if (!$folder) return false;
            if ($this->permisos('folders', $id)) return true;
            $id = (int) ($folder->carpeta_padre_id ?? 0);
        }
        return false;
    }

    private function canFile(object $file): bool
    {
        return $this->permisos('files', (int) $file->id)
            || ($file->carpeta_id !== null && $this->canFolder((int) $file->carpeta_id));
    }

    private function folderData(object $f): array
    {
        return ['id' => $f->id, 'type' => 'folder', 'name' => $f->nombre,
            'modified' => $f->updated_at, 'parent' => $f->carpeta_padre_id,
            'owner_id' => $f->creado_por, 'owner' => $this->ownerName((int)$f->creado_por)];
    }

    private function fileData(object $f): array
    {
        $disk = Storage::disk('documentos_privados');
        return ['id' => $f->id, 'type' => 'file', 'name' => $f->nombre_original,
            'modified' => $f->updated_at, 'parent' => $f->carpeta_id,
            'owner_id' => $f->subido_por, 'owner' => $this->ownerName((int)$f->subido_por),
            'size' => $disk->exists($f->ruta_archivo) ? $disk->size($f->ruta_archivo) : 0];
    }

    private function ownerName(int $uid): string
    {
        $u = DB::table('users')->where('id', $uid)->first();
        return trim(($u->nombre ?? $u->name ?? '') . ' ' . ($u->apellido ?? '')) ?: 'Otro usuario';
    }

    public function listing(Request $request)
    {
        $cfg = $this->config();
        $data = $request->validate(['carpeta_id' => ['nullable','integer','min:1']]);
        $parent = isset($data['carpeta_id']) ? (int)$data['carpeta_id'] : null;
        $uid = (int) auth()->id();
        $items = [];
        $trail = [];
        if ($parent !== null) {
            abort_unless($this->canFolder($parent), 403, 'No tienes acceso a esta carpeta.');
            $cursor = $parent; $seen = [];
            while ($cursor && !isset($seen[$cursor])) {
                $seen[$cursor] = true;
                $f = DB::table('documento_carpetas')->where('id', $cursor)->whereNull('deleted_at')->first();
                if (!$f) break;
                array_unshift($trail, ['id' => $f->id, 'name' => $f->nombre]);
                $cursor = $f->carpeta_padre_id;
            }
            $folders = DB::table('documento_carpetas')->where('carpeta_padre_id', $parent)->whereNull('deleted_at')->orderBy('nombre')->get();
            $files = DB::table('documentos')->where('carpeta_id', $parent)->whereNull('deleted_at')->orderBy('nombre_original')->get();
            foreach ($folders as $f) if ((int)$f->creado_por !== $uid && $this->canFolder((int)$f->id)) $items[] = $this->folderData($f);
            foreach ($files as $f) if ((int)$f->subido_por !== $uid && $this->canFile($f)) $items[] = $this->fileData($f);
        } else {
            $grants = DB::table(self::PERMISOS)->where($cfg['destinatario'], $uid)
                ->when(Schema::hasColumn(self::PERMISOS, 'deleted_at'), fn($q) => $q->whereNull('deleted_at'))->get();
            $found = [];
            foreach ($grants as $g) {
                $type = strtolower((string)$g->{$cfg['tipo']});
                $id = (int)$g->{$cfg['id']};
                if (in_array($type, ['folders','folder','carpeta','documento_carpeta','app\\models\\documentocarpeta'], true)) {
                    $f = DB::table('documento_carpetas')->where('id',$id)->whereNull('deleted_at')->first();
                    if ($f && (int)$f->creado_por !== $uid && !isset($found['folder:'.$id])) { $items[] = $this->folderData($f); $found['folder:'.$id] = true; }
                } elseif (in_array($type, ['files','file','archivo','documento','app\\models\\documento'], true)) {
                    $f = DB::table('documentos')->where('id',$id)->whereNull('deleted_at')->first();
                    if ($f && (int)$f->subido_por !== $uid && !isset($found['file:'.$id])) { $items[] = $this->fileData($f); $found['file:'.$id] = true; }
                }
            }
        }
        return response()->json(['items' => $items, 'trail' => $trail]);
    }

    private function accessibleFile(int $id): object
    {
        $f = DB::table('documentos')->where('id',$id)->whereNull('deleted_at')->firstOrFail();
        abort_unless((int)$f->subido_por !== (int)auth()->id() && $this->canFile($f), 403, 'No tienes acceso al documento.');
        // Si un ancestro fue eliminado, no exponer el documento.
        $cursor = $f->carpeta_id; $seen = [];
        while ($cursor !== null) {
            abort_unless(!isset($seen[$cursor]), 403);
            $seen[$cursor] = true;
            $folder = DB::table('documento_carpetas')->where('id', $cursor)->whereNull('deleted_at')->first();
            abort_unless($folder, 404);
            $cursor = $folder->carpeta_padre_id;
        }
        return $f;
    }

    public function download(int $id)
    {
        $f = $this->accessibleFile($id);
        $disk = Storage::disk('documentos_privados');
        abort_unless($disk->exists($f->ruta_archivo), 404, 'Archivo físico no encontrado.');
        return $disk->download($f->ruta_archivo, $f->nombre_original);
    }

    public function preview(int $id): BinaryFileResponse
    {
        $f = $this->accessibleFile($id);
        $disk = Storage::disk('documentos_privados');
        abort_unless($disk->exists($f->ruta_archivo), 404);
        $mime = $disk->mimeType($f->ruta_archivo);
        abort_unless(in_array($mime, ['application/pdf','image/png','image/jpeg'], true), 415, 'Formato sin vista previa; descárgalo.');
        return response()->file($disk->path($f->ruta_archivo), [
            'Content-Type' => $mime, 'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }
}

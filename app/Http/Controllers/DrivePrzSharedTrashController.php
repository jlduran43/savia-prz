<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DrivePrzSharedTrashController extends Controller
{
    private function table(string $kind): string
    {
        abort_unless(in_array($kind, ['files', 'folders'], true), 404);
        return $kind === 'files' ? 'documentos' : 'documento_carpetas';
    }

    private function ownerColumn(string $kind): string
    {
        return $kind === 'files' ? 'subido_por' : 'creado_por';
    }

    private function grantTypes(string $kind): array
    {
        return $kind === 'files'
            ? ['files', 'file', 'archivo', 'documento', 'App\\Models\\Documento']
            : ['folders', 'folder', 'carpeta', 'documento_carpeta', 'App\\Models\\DocumentoCarpeta'];
    }

    private function permissions(object $grant): array
    {
        // Los permisos individuales son la fuente de verdad; 'edit' no implica eliminar.
        foreach (['permisos', 'permisos_json'] as $column) {
            if (isset($grant->{$column}) && trim((string) $grant->{$column}) !== '') {
                $decoded = json_decode((string) $grant->{$column}, true);
                return is_array($decoded) ? $decoded : [];
            }
        }
        return []; // Nunca conceder eliminar mediante un permiso heredado 'edit'.
    }

    private function directGrant(string $kind, int $id, int $userId): bool
    {
        return DB::table('drive_prz_permisos')
            ->whereIn('tipo', $this->grantTypes($kind))
            ->where('elemento_id', $id)
            ->where('usuario_id', $userId)
            ->exists();
    }

    private function canTrash(string $kind, object $entry, int $userId): bool
    {
        $ids = [[$kind, (int) $entry->id]];
        $folderId = $kind === 'files' ? (int) ($entry->carpeta_id ?? 0) : (int) ($entry->carpeta_padre_id ?? 0);
        $visited = [];
        while ($folderId > 0) {
            if (isset($visited[$folderId])) return false;
            $visited[$folderId] = true;
            $folder = DB::table('documento_carpetas')->where('id', $folderId)->whereNull('deleted_at')->first();
            if (!$folder) return false;
            $ids[] = ['folders', $folderId];
            $folderId = (int) ($folder->carpeta_padre_id ?? 0);
        }
        foreach ($ids as [$type, $id]) {
            $grants = DB::table('drive_prz_permisos')
                ->whereIn('tipo', $this->grantTypes($type))
                ->where('elemento_id', $id)
                ->where('usuario_id', $userId)
                ->get();
            foreach ($grants as $grant) {
                if (in_array('eliminar', $this->permissions($grant), true)) return true;
            }
        }
        return false;
    }

    public function hide(string $kind, int $id): JsonResponse
    {
        $table = $this->table($kind);
        $userId = (int) auth()->id();
        $entry = DB::table($table)->where('id', $id)->whereNull('deleted_at')->firstOrFail();
        abort_if((int) $entry->{$this->ownerColumn($kind)} === $userId, 403, 'Utiliza Mis archivos.');
        // Sólo se permite ocultar lo que nos hayan compartido directamente.
        abort_unless($this->directGrant($kind, $id, $userId), 403, 'No tienes un acceso compartido directo a este elemento.');
        DB::table('drive_prz_compartidos_ocultos')->updateOrInsert(
            ['usuario_id' => $userId, 'tipo' => $kind, 'elemento_id' => $id],
            ['updated_at' => now(), 'created_at' => now()]
        );
        Log::info('Drive PRZ: compartido quitado de la lista personal', ['usuario_id' => $userId, 'tipo' => $kind, 'elemento_id' => $id]);
        return response()->json(['ok' => true, 'message' => 'Elemento quitado de tus compartidos.']);
    }

    public function trash(string $kind, int $id): JsonResponse
    {
        $table = $this->table($kind);
        $uid = (int) auth()->id();
        DB::transaction(function () use ($table, $kind, $id, $uid) {
            $entry = DB::table($table)->where('id', $id)->whereNull('deleted_at')->lockForUpdate()->firstOrFail();
            abort_if((int) $entry->{$this->ownerColumn($kind)} === $uid, 403, 'Utiliza Mis archivos.');
            abort_unless($this->canTrash($kind, $entry, $uid), 403, 'El propietario no autorizó mover este elemento a la papelera.');
            DB::table($table)->where('id', $id)->whereNull('deleted_at')
                ->update(['deleted_at' => now(), 'updated_at' => now()]);
        });
        // No borra el archivo físico ni sus subelementos. Restaurar la carpeta
        // permite recuperar su contenido, según la papelera existente.
        Log::warning('Drive PRZ: compartido enviado a papelera', ['actor_id' => $uid, 'tipo' => $kind, 'elemento_id' => $id]);
        return response()->json(['ok' => true, 'message' => 'Enviado a la papelera del propietario.']);
    }
}

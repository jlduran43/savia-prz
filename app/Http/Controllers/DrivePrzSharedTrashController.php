<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Mover archivos ajenos compartidos a la papelera (nunca purgar). */
class DrivePrzSharedTrashController extends Controller
{
    public function moveToTrash(Request $request, int $id): JsonResponse
    {
        $userId = (int) $request->user()->id;
        return DB::transaction(function () use ($id, $userId): JsonResponse {
            $file = DB::table('documentos')->where('id', $id)
                ->whereNull('deleted_at')->lockForUpdate()->first();
            abort_unless($file, 404, 'Archivo no encontrado.');
            abort_if((int) $file->subido_por === $userId, 403,
                'Utiliza Mis archivos para tus documentos.');

            $grants = DB::table('drive_prz_permisos')->where('elemento_id', $id)
                ->whereIn('tipo', ['files', 'file', 'archivo', 'documento', 'App\\Models\\Documento'])
                ->where(function ($q) use ($userId) {
                    $q->where('usuario_id', $userId)->orWhereNull('usuario_id');
                })->get();

            $parent = $file->carpeta_id;
            $seen = [];
            while ($parent !== null) {
                $parent = (int) $parent;
                abort_if(isset($seen[$parent]), 409, 'Estructura circular de carpetas.');
                $seen[$parent] = true;
                $folder = DB::table('documento_carpetas')->where('id', $parent)
                    ->whereNull('deleted_at')->first();
                abort_unless($folder, 404, 'La carpeta superior no existe.');
                $grants = $grants->concat(DB::table('drive_prz_permisos')
                    ->where('tipo', 'folders')->where('elemento_id', $parent)
                    ->where(function ($q) use ($userId) {
                        $q->where('usuario_id', $userId)->orWhereNull('usuario_id');
                    })->get());
                $parent = $folder->carpeta_padre_id;
            }

            $canDelete = $grants->contains(function ($grant) {
                // El rol edit por sí solo NO autoriza eliminar.
                foreach (['permisos', 'permisos_json'] as $column) {
                    if (isset($grant->{$column}) && $grant->{$column} !== '') {
                        $decoded = json_decode((string) $grant->{$column}, true);
                        if (is_array($decoded)) return in_array('eliminar', $decoded, true);
                    }
                }
                return false;
            });
            abort_unless($canDelete, 403, 'El propietario no te autorizó a mover este archivo a la papelera.');

            DB::table('documentos')->where('id', $id)->whereNull('deleted_at')
                ->update(['deleted_at' => now(), 'updated_at' => now()]);
            return response()->json([
                'ok' => true,
                'message' => 'Archivo enviado a la papelera del propietario.',
            ]);
        });
    }
}

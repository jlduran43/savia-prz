<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class DrivePrzRenameController extends Controller
{
    public function rename(Request $request, string $tipo, int $id): JsonResponse
    {
        abort_unless(in_array($tipo, ['files', 'folders'], true), 404);
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:180'],
        ]);
        $nombre = trim($data['nombre']);
        if ($nombre === '' || in_array($nombre, ['.', '..'], true)
            || preg_match('~[/\\\\\x00-\x1F\x7F]~u', $nombre)
            || str_ends_with($nombre, '.') || str_ends_with($nombre, ' ')) {
            throw ValidationException::withMessages(['nombre' => 'Escribe un nombre válido.']);
        }

        $tabla = $tipo === 'folders' ? 'documento_carpetas' : 'documentos';
        $propietario = $tipo === 'folders' ? 'creado_por' : 'subido_por';
        $padre = $tipo === 'folders' ? 'carpeta_padre_id' : 'carpeta_id';
        $hasSoftDeletes = Schema::hasColumn($tabla, 'deleted_at');
        $hasUpdatedAt = Schema::hasColumn($tabla, 'updated_at');
        $hasOriginal = $tipo === 'files' && Schema::hasColumn($tabla, 'nombre_original');

        return DB::transaction(function () use ($request, $tabla, $propietario, $padre, $hasSoftDeletes, $hasUpdatedAt, $hasOriginal, $nombre, $tipo, $id) {
            $elemento = DB::table($tabla)->where('id', $id)->lockForUpdate()->first();
            abort_if(!$elemento || ($hasSoftDeletes && $elemento->deleted_at !== null), 404, 'Elemento no encontrado.');
            // Solo el propietario puede cambiar nombres. Nunca confiar en permisos enviados por el navegador.
            abort_unless((int) $elemento->{$propietario} === (int) $request->user()->id, 403, 'No tienes permiso para cambiar el nombre.');

            if ($tipo === 'files') {
                $anteriorExt = strtolower(pathinfo($elemento->nombre, PATHINFO_EXTENSION));
                $nuevaExt = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
                if ($anteriorExt !== $nuevaExt) {
                    throw ValidationException::withMessages(['nombre' => 'No puedes modificar la extensión del archivo.']);
                }
            }

            $query = DB::table($tabla)->where('id', '!=', $id)
                ->whereRaw('LOWER(nombre) = ?', [mb_strtolower($nombre)]);
            if ($elemento->{$padre} === null) $query->whereNull($padre);
            else $query->where($padre, $elemento->{$padre});
            if ($hasSoftDeletes) $query->whereNull('deleted_at');
            if ($query->exists()) {
                throw ValidationException::withMessages(['nombre' => 'Ya existe un elemento de este tipo con ese nombre en esta carpeta.']);
            }

            $cambios = ['nombre' => $nombre];
            if ($hasOriginal) $cambios['nombre_original'] = $nombre;
            if ($hasUpdatedAt) $cambios['updated_at'] = now();
            DB::table($tabla)->where('id', $id)->update($cambios);

            return response()->json(['ok' => true, 'id' => $id, 'tipo' => $tipo, 'nombre' => $nombre]);
        });
    }
}

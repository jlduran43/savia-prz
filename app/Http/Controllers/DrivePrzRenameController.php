<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        if (
            $nombre === '' ||
            in_array($nombre, ['.', '..'], true) ||
            preg_match('~[/\\\\\x00-\x1F\x7F]~u', $nombre) ||
            str_ends_with($nombre, '.')
        ) {
            throw ValidationException::withMessages([
                'nombre' => 'Escribe un nombre válido.',
            ]);
        }

        $esArchivo = $tipo === 'files';

        $tabla = $esArchivo
            ? 'documentos'
            : 'documento_carpetas';

        $columnaNombre = $esArchivo
            ? 'nombre_original'
            : 'nombre';

        $columnaPropietario = $esArchivo
            ? 'subido_por'
            : 'creado_por';

        $columnaPadre = $esArchivo
            ? 'carpeta_id'
            : 'carpeta_padre_id';

        return DB::transaction(function () use (
            $request,
            $id,
            $tipo,
            $nombre,
            $esArchivo,
            $tabla,
            $columnaNombre,
            $columnaPropietario,
            $columnaPadre
        ) {

            // 1. Buscar el elemento y bloquearlo durante la operación.
            $elemento = DB::table($tabla)
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->first();

            abort_unless(
                $elemento,
                404,
                'El archivo o carpeta no existe.'
            );

            // 2. Comprobar propietario.
            abort_unless(
                (int) $elemento->{$columnaPropietario}
                    === (int) $request->user()->id,
                403,
                'No tienes permiso para cambiar el nombre.'
            );

            // 3. Verificar extensión para archivos.
            if ($esArchivo) {

                $extensionAnterior = strtolower(
                    pathinfo(
                        $elemento->nombre_original,
                        PATHINFO_EXTENSION
                    )
                );

                $extensionNueva = strtolower(
                    pathinfo($nombre, PATHINFO_EXTENSION)
                );

                if ($extensionAnterior !== $extensionNueva) {
                    throw ValidationException::withMessages([
                        'nombre' => 'No puedes modificar la extensión del archivo.',
                    ]);
                }
            }

            // 4. Comprobar si ya existe otro elemento
            // del mismo tipo con ese nombre en la misma carpeta.
            $duplicado = DB::table($tabla)
                ->where('id', '!=', $id)
                ->whereNull('deleted_at')
                ->whereRaw(
                    "LOWER({$columnaNombre}) = ?",
                    [mb_strtolower($nombre)]
                );

            if ($elemento->{$columnaPadre} === null) {
                $duplicado->whereNull($columnaPadre);
            } else {
                $duplicado->where(
                    $columnaPadre,
                    $elemento->{$columnaPadre}
                );
            }

            if ($duplicado->exists()) {
                throw ValidationException::withMessages([
                    'nombre' => 'Ya existe un elemento con ese nombre en esta carpeta.',
                ]);
            }

            // 5. Actualizar únicamente el nombre visible.
            DB::table($tabla)
                ->where('id', $id)
                ->update([
                    $columnaNombre => $nombre,
                    'updated_at' => now(),
                ]);

            // No modificar ruta_archivo ni nombre_almacenado.
            // Los documentos físicos permanecen intactos.

            return response()->json([
                'ok' => true,
                'id' => $id,
                'tipo' => $tipo,
                'nombre' => $nombre,
                'message' => 'Nombre actualizado correctamente.',
            ]);
        });
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrivePrzOcultarCompartidoController extends Controller
{
    public function ocultar(Request $request, string $kind, int $id): JsonResponse
    {
        abort_unless(in_array($kind, ['files', 'folders'], true), 404);

        $usuarioId = (int) $request->user()->id;
        $esArchivo = $kind === 'files';
        $tabla = $esArchivo ? 'documentos' : 'documento_carpetas';
        $campoPropietario = $esArchivo ? 'subido_por' : 'creado_por';

        $elemento = DB::table($tabla)
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        abort_unless($elemento, 404, 'El elemento no existe.');
        abort_if((int) $elemento->{$campoPropietario} === $usuarioId, 403,
            'No puedes ocultar tus propios archivos desde Compartidos conmigo.');

        // El usuario debe disponer de acceso compartido directo o heredado.
        // No se concede acceso nuevo: solamente se comprueba el ya existente.
        $tipos = $esArchivo
            ? ['files', 'file', 'archivo', 'documento', 'App\\Models\\Documento']
            : ['folders', 'folder', 'carpeta', 'documento_carpeta', 'App\\Models\\DocumentoCarpeta'];

        $concesiones = DB::table('drive_prz_permisos')
            ->whereIn('tipo', $tipos)
            ->where('elemento_id', $id)
            ->where(function ($q) use ($usuarioId) {
                $q->where('usuario_id', $usuarioId)->orWhereNull('usuario_id');
            })->get();

        $carpetaId = $esArchivo ? $elemento->carpeta_id : $elemento->carpeta_padre_id;
        $visitadas = [];
        while ($carpetaId !== null) {
            $carpetaId = (int) $carpetaId;
            abort_if(isset($visitadas[$carpetaId]), 409, 'Carpetas circulares.');
            $visitadas[$carpetaId] = true;

            $carpeta = DB::table('documento_carpetas')
                ->where('id', $carpetaId)->whereNull('deleted_at')->first();
            abort_unless($carpeta, 404, 'La carpeta superior no existe.');

            $concesiones = $concesiones->concat(
                DB::table('drive_prz_permisos')
                    ->where('tipo', 'folders')
                    ->where('elemento_id', $carpetaId)
                    ->where(function ($q) use ($usuarioId) {
                        $q->where('usuario_id', $usuarioId)->orWhereNull('usuario_id');
                    })->get()
            );
            $carpetaId = $carpeta->carpeta_padre_id;
        }

        $puedeVer = $concesiones->contains(function ($concesion) {
            foreach (['permisos', 'permisos_json'] as $columna) {
                if (isset($concesion->{$columna}) && $concesion->{$columna} !== '') {
                    $permisos = json_decode((string) $concesion->{$columna}, true);
                    if (is_array($permisos)) {
                        return in_array('ver', $permisos, true);
                    }
                }
            }
            return in_array(strtolower((string) ($concesion->permiso ?? '')),
                ['view', 'ver', 'edit', 'editar'], true);
        });
        abort_unless($puedeVer, 403, 'No tienes acceso compartido a este elemento.');

        DB::table('drive_prz_compartidos_ocultos')->updateOrInsert(
            ['usuario_id' => $usuarioId, 'tipo' => $kind, 'elemento_id' => $id],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return response()->json(['ok' => true, 'message' => 'Elemento quitado de Compartidos conmigo.']);
    }
}

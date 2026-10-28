<?php

namespace App\Support;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use Illuminate\Support\Facades\DB;

/** Validación central; nunca confiar en botones deshabilitados del HTML. */
final class DrivePrzPermisos
{
    private const ACTIONS = ['view','download','copy','rename','move','delete','upload','create_folder'];

    public static function permite(string $tipo, int $id, string $accion, int $usuarioId): bool
    {
        if (!in_array($tipo, ['files','folders'], true) || !in_array($accion, self::ACTIONS, true)) return false;
        $item = $tipo === 'files' ? Documento::find($id) : DocumentoCarpeta::find($id);
        if (!$item) return false;
        $propietario = $tipo === 'files' ? $item->subido_por : $item->creado_por;
        if ((int)$propietario === $usuarioId) return true;
        if (!DB::table('users')->where('id',$usuarioId)->where('activo',1)->exists()) return false;

        $grants = [[$tipo, $id]];
        $parent = $tipo === 'files' ? $item->carpeta_id : $item->carpeta_padre_id;
        $visited = [];
        for ($n = 0; $parent !== null && $n < 100; $n++) {
            if (isset($visited[$parent])) return false;
            $visited[$parent] = true;
            $folder = DocumentoCarpeta::find($parent);
            if (!$folder) break;
            $grants[] = ['folders',(int)$folder->id];
            $parent = $folder->carpeta_padre_id;
        }
        foreach ($grants as [$kind, $elementId]) {
            $rows = DB::table('drive_prz_permisos')->where('tipo',$kind)->where('elemento_id',$elementId)
                ->where(function ($q) use ($usuarioId) { $q->where('usuario_id',$usuarioId)->orWhereNull('usuario_id'); })->get();
            foreach ($rows as $row) {
                if ($row->permiso === 'edit') return true;
                if ($row->permiso === 'view' && in_array($accion,['view','download'],true)) return true;
                if ($row->permiso === 'custom') {
                    $flags = json_decode((string)$row->permisos_json,true) ?: [];
                    if (!empty($flags[$accion])) return true;
                }
            }
        }
        return false;
    }

    public static function exigir(string $tipo, int $id, string $accion): void
    {
        abort_unless(auth()->check() && self::permite($tipo,$id,$accion,(int)auth()->id()), 403,
            'No tienes permiso para realizar esta acción.');
    }
}
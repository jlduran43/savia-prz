<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrivePrzNotificacionController extends Controller
{
    private const TABLA = 'drive_prz_notificaciones';

    // Obtener las notificaciones del usuario conectado.
    public function index(Request $request): JsonResponse
    {
        $usuarioId = (int) $request->user()->id;

        $notificaciones = DB::table(self::TABLA . ' as n')
            ->join('users as u', 'u.id', '=', 'n.remitente_id')
            ->where('n.usuario_id', $usuarioId)
            ->select(
                'n.id',
                'n.tipo',
                'n.elemento_tipo',
                'n.elemento_id',
                'n.elemento_nombre',
                'n.mensaje',
                'n.leida_at',
                'n.created_at',
                'u.nombre as remitente_nombre',
                'u.apellido as remitente_apellido'
            )
            ->orderByDesc('n.created_at')
            ->limit(30)
            ->get();

        $sinLeer = DB::table(self::TABLA)
            ->where('usuario_id', $usuarioId)
            ->whereNull('leida_at')
            ->count();

        return response()->json([
            'ok' => true,
            'sin_leer' => $sinLeer,
            'notificaciones' => $notificaciones,
        ]);
    }

    // Marcar una notificación como leída.
    public function marcarLeida(
        Request $request,
        int $id
    ): JsonResponse {
        $usuarioId = (int) $request->user()->id;

        $actualizados = DB::table(self::TABLA)
            ->where('id', $id)
            ->where('usuario_id', $usuarioId)
            ->update([
                'leida_at' => now(),
                'updated_at' => now(),
            ]);

        if (!$actualizados) {
            return response()->json([
                'ok' => false,
                'message' => 'Notificación no encontrada.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Notificación marcada como leída.',
        ]);
    }
}
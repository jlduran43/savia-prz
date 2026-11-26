<?php

namespace App\Http\Controllers;

use App\Models\DrivePrzEstructura;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DrivePrzEstructuraController extends Controller
{
    public function index(Request $request)
    {
        $estructuras = DrivePrzEstructura::query()
            ->where('usuario_id', $request->user()->id)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'estructura']);

        return response()->json($estructuras);
    }

    public function store(Request $request)
    {
        $usuarioId = $request->user()->id;

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('drive_prz_estructuras', 'nombre')
                    ->where('usuario_id', $usuarioId),
            ],
            'estructura' => [
                'required',
                'string',
            ],
        ], [
            'nombre.unique' =>
                'Ya tienes una estructura guardada con ese nombre.',
        ]);

        $estructura = DrivePrzEstructura::create([
            'usuario_id' => $usuarioId,
            'nombre' => $datos['nombre'],
            'estructura' => $datos['estructura'],
        ]);

        return response()->json([
            'message' => 'Estructura guardada correctamente.',
            'estructura' => $estructura,
        ], 201);
    }

    public function destroy(Request $request, int $id)
    {
        $estructura = DrivePrzEstructura::query()
            ->where('usuario_id', $request->user()->id)
            ->findOrFail($id);

        $estructura->delete();

        return response()->json([
            'message' => 'Estructura eliminada correctamente.',
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\DrivePrzConfiguracion;
use App\Support\DrivePrzAjustes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrivePrzConfiguracionController extends Controller
{
    private function configuracion(): DrivePrzConfiguracion
    {
        return DrivePrzConfiguracion::query()->firstOrCreate([], [
            'marca_agua_texto' => 'DRIVE PRZ - SOLO LECTURA',
            'marca_agua_color' => '#14263D',
            'marca_agua_opacidad' => 22,
            'marca_agua_angulo' => -25,
            'usar_tamano_manual' => false,
            'marca_agua_tamano' => null,
        ]);
    }

    public function mostrar(): JsonResponse
    {
        return response()->json($this->configuracion()->only([
            'marca_agua_texto', 'marca_agua_color', 'marca_agua_opacidad',
            'marca_agua_angulo', 'usar_tamano_manual', 'marca_agua_tamano',
        ]));
    }

    public function guardar(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'marca_agua_texto' => ['required', 'string', 'max:255'],
            'marca_agua_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'marca_agua_opacidad' => ['required', 'integer', 'between:1,100'],
            'marca_agua_angulo' => ['required', 'integer', 'between:-360,360'],
            'usar_tamano_manual' => ['required', 'boolean'],
            'marca_agua_tamano' => ['nullable', 'required_if:usar_tamano_manual,true', 'integer', 'between:10,120'],
        ]);

        if (! $datos['usar_tamano_manual']) {
            $datos['marca_agua_tamano'] = null;
        }

        $configuracion = $this->configuracion();
        $configuracion->fill($datos);
        $configuracion->save();
        DrivePrzAjustes::limpiarCache();

        return response()->json(['message' => 'Configuración guardada correctamente.']);
    }
}

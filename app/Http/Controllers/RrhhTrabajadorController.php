<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RrhhTrabajador;
use App\Models\RrhhContrato;
use App\Models\RrhhHistorialLaboral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RrhhTrabajadorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Autorizar acceso
    |--------------------------------------------------------------------------
    */

    private function autorizar(): void
    {
        Gate::authorize('rrhh-gestionar-trabajadores');
    }

    /*
    |--------------------------------------------------------------------------
    | Normalizar RUT
    |--------------------------------------------------------------------------
    */

    private function normalizarRut(string $rut): string
    {
        return strtoupper(
            preg_replace('/[^0-9kK]/', '', $rut)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validar dígito verificador del RUT
    |--------------------------------------------------------------------------
    */

    private function rutValido(string $rut): bool
    {
        $rut = $this->normalizarRut($rut);

        if (!preg_match('/^[1-9][0-9]{5,7}[0-9K]$/', $rut)) {
            return false;
        }

        $numero = substr($rut, 0, -1);
        $dv = substr($rut, -1);

        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {

            $suma += (int) $numero[$i] * $multiplicador;

            $multiplicador = $multiplicador === 7
                ? 2
                : $multiplicador + 1;
        }

        $resultado = 11 - ($suma % 11);

        $dvCalculado = match ($resultado) {
            11 => '0',
            10 => 'K',
            default => (string) $resultado,
        };

        return $dv === $dvCalculado;
    }

    /*
    |--------------------------------------------------------------------------
    | Validaciones de datos personales
    |--------------------------------------------------------------------------
    */

    private function validarTrabajador(
        Request $request,
        ?int $id = null
    ): array {

        $request->merge([
            'rut' => $this->normalizarRut(
                (string) $request->input('rut')
            ),
        ]);

        $uniqueRut = Rule::unique(
            'rrhh_trabajadores',
            'rut'
        );

        $uniqueUsuario = Rule::unique(
            'rrhh_trabajadores',
            'usuario_id'
        );

        if ($id !== null) {
            $uniqueRut->ignore($id);
            $uniqueUsuario->ignore($id);
        }

        $datos = $request->validate([

            'usuario_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                $uniqueUsuario,
            ],

            'rut' => [
                'required',
                'string',
                'max:15',
                $uniqueRut,
            ],

            'nombres' => [
                'required',
                'string',
                'max:150',
            ],

            'apellido_paterno' => [
                'required',
                'string',
                'max:80',
            ],

            'apellido_materno' => [
                'nullable',
                'string',
                'max:80',
            ],

            'fecha_nacimiento' => [
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:today',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:30',
            ],

            'formacion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'fecha_ingreso' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:fecha_nacimiento',
            ],

        ], [
            'rut.required' => 'Debes ingresar el RUT.',
            'rut.unique' => 'Ya existe un trabajador con este RUT.',
            'nombres.required' => 'Debes ingresar los nombres.',
            'apellido_paterno.required' => 'Debes ingresar el primer apellido.',
            'usuario_id.unique' => 'Este usuario ya tiene una ficha laboral.',
            'fecha_ingreso.required' => 'Debes ingresar la fecha de ingreso.',
        ]);

        if (!$this->rutValido($datos['rut'])) {

            throw ValidationException::withMessages([
                'rut' => 'El RUT ingresado no es válido.',
            ]);
        }

        // Comprobar que el usuario vinculado corresponda
        // efectivamente al trabajador registrado.

        if (!empty($datos['usuario_id'])) {

            $usuario = User::findOrFail(
                $datos['usuario_id']
            );

            if (
                empty($usuario->rut) ||
                $this->normalizarRut($usuario->rut)
                    !== $datos['rut']
            ) {
                throw ValidationException::withMessages([
                    'usuario_id' =>
                        'El RUT del usuario no coincide con el trabajador.',
                ]);
            }
        }

        return $datos;
    }

    /*
    |--------------------------------------------------------------------------
    | Validar información contractual inicial
    |--------------------------------------------------------------------------
    */

    private function validarContrato(Request $request): array
    {
        $datos = $request->validate([

            'tipo_contrato' => [
                'required',
                Rule::in([
                    'indefinido',
                    'plazo_fijo',
                    'honorarios',
                ]),
            ],

            'tipo_jornada' => [
                'nullable',
                'required_if:tipo_contrato,indefinido,plazo_fijo',
                Rule::in([
                    'full_time',
                    'part_time',
                ]),
            ],

            'detalle_part_time' => [
                'nullable',
                'required_if:tipo_jornada,part_time',
                Rule::in([
                    'a_llamada',
                    '30_hrs',
                    '20_hrs',
                    'otro',
                ]),
            ],

            'otra_jornada' => [
                'nullable',
                'required_if:detalle_part_time,otro',
                'string',
                'max:150',
            ],

            'area' => [
                'nullable',
                'string',
                'max:150',
            ],

            'cargo' => [
                'required',
                'string',
                'max:255',
            ],

            'jefatura_directa' => [
                'nullable',
                'string',
                'max:255',
            ],

            'estado_vinculo' => [
                'required',
                'string',
                'max:80',
            ],

            'situacion_administrativa' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fecha_inicio' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:fecha_ingreso',
            ],

            'fecha_termino' => [
                'nullable',
                'required_if:tipo_contrato,plazo_fijo',
                'date_format:Y-m-d',
                'after_or_equal:fecha_inicio',
            ],

            'fecha_desvinculacion' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:fecha_inicio',
            ],

        ]);

        // Los contratos indefinidos no tienen
        // una fecha de término contractual programada.

        if ($datos['tipo_contrato'] === 'indefinido') {
            $datos['fecha_termino'] = null;
        }

        // Honorarios no utiliza la clasificación
        // de jornada del contrato laboral.

        if ($datos['tipo_contrato'] === 'honorarios') {

            $datos['tipo_jornada'] = null;
            $datos['detalle_part_time'] = null;
            $datos['otra_jornada'] = null;
        }

        if (($datos['tipo_jornada'] ?? null) !== 'part_time') {
            $datos['detalle_part_time'] = null;
            $datos['otra_jornada'] = null;
        }

        if (($datos['detalle_part_time'] ?? null) !== 'otro') {
            $datos['otra_jornada'] = null;
        }

        $datos['jornada_contractual'] =
            $this->obtenerJornada($datos);

        return $datos;
    }

    /*
    |--------------------------------------------------------------------------
    | Generar descripción de jornada
    |--------------------------------------------------------------------------
    */

    private function obtenerJornada(array $datos): ?string
    {
        if (($datos['tipo_jornada'] ?? null) === 'full_time') {
            return 'Full time';
        }

        if (($datos['tipo_jornada'] ?? null) === 'part_time') {

            $detalle = match ($datos['detalle_part_time'] ?? null) {
                'a_llamada' => 'A llamada',
                '30_hrs' => '30 horas',
                '20_hrs' => '20 horas',
                'otro' => $datos['otra_jornada'] ?? '',
                default => '',
            };

            return $detalle
                ? 'Part time - ' . $detalle
                : 'Part time';
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Listar trabajadores
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $this->autorizar();

        $request->validate([
            'buscar' => ['nullable', 'string', 'max:150'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $buscar = trim(
            (string) $request->input('buscar', '')
        );

        $query = RrhhTrabajador::query()
            ->withCount('contratos');

        if ($buscar !== '') {

            $query->where(function ($q) use ($buscar) {

                $q->where('nombres', 'like', "%{$buscar}%")
                    ->orWhere(
                        'apellido_paterno',
                        'like',
                        "%{$buscar}%"
                    )
                    ->orWhere(
                        'apellido_materno',
                        'like',
                        "%{$buscar}%"
                    )
                    ->orWhere(
                        'rut',
                        'like',
                        "%{$buscar}%"
                    );
            });
        }

        if ($request->filled('activo')) {

            $query->where(
                'activo',
                $request->boolean('activo')
            );
        }

        $trabajadores = $query
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->paginate(50, [
                'id',
                'usuario_id',
                'rut',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'fecha_ingreso',
                'activo',
            ]);

        return response()->json($trabajadores);
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar ficha individual
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        $this->autorizar();

        $trabajador = RrhhTrabajador::with([

            'usuario:id,nombre,email',

            'contratos' => fn ($query) =>
                $query->orderByDesc('fecha_inicio')
                    ->orderByDesc('id'),

            'historialLaboral' => fn ($query) =>
                $query->orderByDesc('fecha_efectiva')
                    ->orderByDesc('id'),

        ])->findOrFail($id);

        return response()->json([
            'trabajador' => $trabajador,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar trabajador
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->autorizar();

        $datosTrabajador =
            $this->validarTrabajador($request);

        $datosContrato =
            $this->validarContrato($request);

        $trabajador = DB::transaction(function () use (
            $datosTrabajador,
            $datosContrato
        ) {

            // 1. Crear ficha personal.

            $trabajador = RrhhTrabajador::create([
                ...$datosTrabajador,
                'activo' => true,
            ]);

            // 2. Crear contrato inicial.

            $contrato = $trabajador
                ->contratos()
                ->create($datosContrato);

            // 3. Registrar primer movimiento laboral.

            $trabajador->historialLaboral()->create([

                'contrato_id' => $contrato->id,

                'registrado_por' => auth()->id(),

                'tipo_movimiento' =>
                    RrhhHistorialLaboral::INGRESO,

                'fecha_efectiva' =>
                    $trabajador->fecha_ingreso,

                'antes' => null,

                'despues' => [
                    'tipo_contrato' => $contrato->tipo_contrato,
                    'tipo_jornada' => $contrato->tipo_jornada,
                    'cargo' => $contrato->cargo,
                    'area' => $contrato->area,
                    'jefatura_directa' => $contrato->jefatura_directa,
                    'fecha_inicio' => $contrato->fecha_inicio?->toDateString(),
                ],

                'motivo' =>
                    'Registro inicial de la ficha laboral en SAVIA PRZ.',

            ]);

            return $trabajador->load([
                'contratos',
                'historialLaboral',
            ]);
        });

        return response()->json([

            'ok' => true,

            'message' =>
                'Trabajador registrado correctamente.',

            'trabajador' => $trabajador,

        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar datos personales
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, int $id)
    {
        $this->autorizar();

        $trabajador = RrhhTrabajador::findOrFail($id);

        $datos = $this->validarTrabajador(
            $request,
            $trabajador->id
        );

        // Las fechas de ingreso se modificarán mediante
        // una operación laboral específica.

        unset($datos['fecha_ingreso']);

        DB::transaction(function () use ($trabajador, $datos) {

            $trabajador = RrhhTrabajador::query()
                ->lockForUpdate()
                ->findOrFail($trabajador->id);

            $trabajador->fill($datos);

            if (!$trabajador->isDirty()) {
                return;
            }

            $cambios = $trabajador->getDirty();

            $antes = [];

            foreach ($cambios as $campo => $valor) {
                $antes[$campo] =
                    $trabajador->getRawOriginal($campo);
            }

            $trabajador->save();

            $trabajador->historialLaboral()->create([

                'contrato_id' => null,

                'registrado_por' => auth()->id(),

                'tipo_movimiento' =>
                    RrhhHistorialLaboral::ACTUALIZACION_FICHA,

                'fecha_efectiva' => now()->toDateString(),

                'antes' => $antes,

                'despues' => $cambios,

                'motivo' =>
                    'Actualización de datos personales.',

            ]);
        });

        return response()->json([

            'ok' => true,

            'message' =>
                'Ficha actualizada correctamente.',

            'trabajador' => $trabajador->fresh(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener contratos
    |--------------------------------------------------------------------------
    */

    public function contratos(int $id)
    {
        $this->autorizar();

        $trabajador = RrhhTrabajador::findOrFail($id);

        return response()->json([

            'contratos' => $trabajador
                ->contratos()
                ->orderByDesc('fecha_inicio')
                ->orderByDesc('id')
                ->get(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener historial laboral
    |--------------------------------------------------------------------------
    */

    public function historial(int $id)
    {
        $this->autorizar();

        $trabajador = RrhhTrabajador::findOrFail($id);

        return response()->json([

            'historial' => $trabajador
                ->historialLaboral()
                ->with('registradoPor:id,nombre')
                ->orderByDesc('fecha_efectiva')
                ->orderByDesc('id')
                ->get(),

        ]);
    }
}

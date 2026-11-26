<?php

namespace App\Http\Controllers;

use App\Models\RrhhTrabajador;
use App\Models\RrhhTipoDocumento;
use App\Models\RrhhDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RrhhDocumentoController extends Controller
{
    private function autorizar(): void
    {
        Gate::authorize('rrhh-documentacion');
    }

    private function trabajador(int $id): RrhhTrabajador
    {
        return RrhhTrabajador::findOrFail($id);
    }

    private function documento(
        int $trabajadorId,
        int $documentoId
    ): RrhhDocumento {
        return RrhhDocumento::query()
            ->where('trabajador_id', $trabajadorId)
            ->findOrFail($documentoId);
    }

    private function auditar(
        RrhhDocumento $documento,
        string $accion
    ): void {
        DB::table('rrhh_documentos_auditoria')->insert([
            'documento_id' => $documento->id,
            'usuario_id' => auth()->id(),
            'accion' => $accion,
            'detalle' => json_encode([
                'trabajador_id' => $documento->trabajador_id,
                'tipo_documento_id' => $documento->tipo_documento_id,
            ]),
            'created_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener trabajadores registrados
    |--------------------------------------------------------------------------
    */

    public function trabajadores()
    {
        $this->autorizar();

        return response()->json([
            'trabajadores' => RrhhTrabajador::query()
                ->orderBy('apellido_paterno')
                ->orderBy('apellido_materno')
                ->orderBy('nombres')
                ->get([
                    'id',
                    'usuario_id',
                    'nombres',
                    'apellido_paterno',
                    'apellido_materno',
                    'activo',
                ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener categorías y cantidad de documentos
    |--------------------------------------------------------------------------
    */

    public function tipos(int $trabajadorId)
    {
        $this->autorizar();
        $this->trabajador($trabajadorId);

        $tipos = RrhhTipoDocumento::query()
            ->where('activo', true)
            ->withCount([
                'documentos' => function ($query) use ($trabajadorId) {
                    $query->where(
                        'trabajador_id',
                        $trabajadorId
                    );
                },
            ])
            ->orderBy('orden')
            ->get();

        return response()->json([
            'tipos' => $tipos,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Listar documentos de un trabajador
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        int $trabajadorId
    ) {
        $this->autorizar();
        $this->trabajador($trabajadorId);

        $data = $request->validate([
            'tipo_documento_id' => ['nullable', 'integer'],
            'anio' => ['nullable', 'integer', 'between:1900,9999'],
            'mes' => ['nullable', 'integer', 'between:1,12'],
        ]);

        $documentos = RrhhDocumento::query()
            ->where('trabajador_id', $trabajadorId)
            ->with('tipoDocumento:id,nombre,codigo')
            ->when(
                isset($data['tipo_documento_id']),
                fn ($q) => $q->where(
                    'tipo_documento_id',
                    $data['tipo_documento_id']
                )
            )
            ->when(
                isset($data['anio']),
                fn ($q) => $q->where('anio', $data['anio'])
            )
            ->when(
                isset($data['mes']),
                fn ($q) => $q->where('mes', $data['mes'])
            )
            ->orderByDesc('anio')
            ->orderByDesc('mes')
            ->latest()
            ->get();

        return response()->json([
            'documentos' => $documentos,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Subir documentos
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        int $trabajadorId
    ) {
        $this->autorizar();

        $trabajador = $this->trabajador($trabajadorId);

        $data = $request->validate([
            'tipo_documento_id' => [
                'required',
                'integer',
                'exists:rrhh_tipos_documentos,id',
            ],

            'archivos' => [
                'required',
                'array',
                'min:1',
                'max:10',
            ],

            'archivos.*' => [
                'required',
                'file',
                'max:20480',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            ],

            'anio' => [
                'nullable',
                'integer',
                'between:1900,9999',
            ],

            'mes' => [
                'nullable',
                'integer',
                'between:1,12',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $tipo = RrhhTipoDocumento::query()
            ->where('activo', true)
            ->findOrFail($data['tipo_documento_id']);

        $anio = $data['anio'] ?? null;
        $mes = $data['mes'] ?? null;

        if ($tipo->requiere_periodo && (!$anio || !$mes)) {
            throw ValidationException::withMessages([
                'anio' => 'Debes seleccionar un año y un mes.',
            ]);
        }

        if ($mes && !$anio) {
            throw ValidationException::withMessages([
                'anio' => 'Debes seleccionar un año.',
            ]);
        }

        $directorio = "trabajadores/{$trabajador->id}/{$tipo->codigo}";

        if ($anio) {
            $directorio .= "/{$anio}";
        }

        if ($mes) {
            $directorio .= '/' . str_pad(
                (string) $mes,
                2,
                '0',
                STR_PAD_LEFT
            );
        }

        $rutasGuardadas = [];

        try {
            $documentos = DB::transaction(function () use (
                $request,
                $data,
                $trabajador,
                $tipo,
                $anio,
                $mes,
                $directorio,
                &$rutasGuardadas
            ) {
                $resultado = [];

                foreach ($request->file('archivos') as $archivo) {

                    $extension = $archivo->extension();

                    $nombreAlmacenado =
                        Str::uuid()->toString() . '.' . $extension;

                    $ruta = $archivo->storeAs(
                        $directorio,
                        $nombreAlmacenado,
                        'rrhh_privado'
                    );

                    if (!$ruta) {
                        throw new \RuntimeException(
                            'No fue posible almacenar el archivo.'
                        );
                    }

                    $rutasGuardadas[] = $ruta;

                    $documento = RrhhDocumento::create([
                        'trabajador_id' => $trabajador->id,
                        'tipo_documento_id' => $tipo->id,

                        'nombre_original' => Str::limit(
                            basename(str_replace(
                                '\\',
                                '/',
                                $archivo->getClientOriginalName()
                            )),
                            255,
                            ''
                        ),

                        'nombre_almacenado' => $nombreAlmacenado,
                        'ruta_archivo' => $ruta,
                        'mime_type' => $archivo->getMimeType(),
                        'tamano' => $archivo->getSize(),

                        'anio' => $anio,
                        'mes' => $mes,

                        'observaciones' =>
                            $data['observaciones'] ?? null,

                        'subido_por' => auth()->id(),
                    ]);

                    $this->auditar($documento, 'SUBIDA');

                    $resultado[] = $documento;
                }

                return $resultado;
            });

            return response()->json([
                'ok' => true,
                'message' => 'Documentos guardados correctamente.',
                'documentos' => $documentos,
            ], 201);

        } catch (\Throwable $e) {

            foreach ($rutasGuardadas as $ruta) {
                try {
                    Storage::disk('rrhh_privado')->delete($ruta);
                } catch (\Throwable $cleanupError) {
                    report($cleanupError);
                }
            }

            report($e);

            return response()->json([
                'message' => 'No fue posible guardar los documentos.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Previsualizar documento
    |--------------------------------------------------------------------------
    */

    public function preview(
        int $trabajadorId,
        int $documentoId
    ) {
        $this->autorizar();
        $this->trabajador($trabajadorId);

        $documento = $this->documento(
            $trabajadorId,
            $documentoId
        );

        $disk = Storage::disk('rrhh_privado');

        abort_unless(
            $disk->exists($documento->ruta_archivo),
            404
        );

        $permitidos = [
            'application/pdf',
            'image/jpeg',
            'image/png',
        ];

        abort_unless(
            in_array($documento->mime_type, $permitidos, true),
            415,
            'Este documento solo permite descarga.'
        );

        $this->auditar($documento, 'VISUALIZACION');

        return response()->file(
            $disk->path($documento->ruta_archivo),
            [
                'Content-Type' => $documento->mime_type,
                'Content-Disposition' => 'inline',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Descargar documento
    |--------------------------------------------------------------------------
    */

    public function download(
        int $trabajadorId,
        int $documentoId
    ) {
        $this->autorizar();
        $this->trabajador($trabajadorId);

        $documento = $this->documento(
            $trabajadorId,
            $documentoId
        );

        $disk = Storage::disk('rrhh_privado');

        abort_unless(
            $disk->exists($documento->ruta_archivo),
            404
        );

        $this->auditar($documento, 'DESCARGA');

        return $disk->download(
            $documento->ruta_archivo,
            $documento->nombre_original,
            [
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mover documento a papelera
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $trabajadorId,
        int $documentoId
    ) {
        $this->autorizar();
        $this->trabajador($trabajadorId);

        $documento = $this->documento(
            $trabajadorId,
            $documentoId
        );

        DB::transaction(function () use ($documento) {
            $documento->delete();

            $this->auditar($documento, 'ELIMINACION');
        });

        return response()->json([
            'ok' => true,
            'message' => 'Documento movido a papelera.',
        ]);
    }
}
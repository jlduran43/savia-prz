<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use App\Models\User;

class ManageDocumentos extends ManageRecords
{
    protected static string $resource = DocumentoResource::class;

    protected string $view = 'filament.resources.documentos.pages.explorador';

    #[Url(as: 'carpeta')]
    public ?int $carpetaId = null;

    #[Url(as: 'buscar')]
    public string $buscar = '';

    #[Url(as: 'vista')]
    public string $vista = 'mios';

    /** Verifica propiedad de la carpeta y de todos sus antecesores. */
    protected function validarCarpetaPropia(?int $carpetaId): void
    {
        abort_unless(auth()->check(), 403);

        $visitadas = [];
        while ($carpetaId !== null) {
            abort_if(in_array($carpetaId, $visitadas, true), 403);
            $visitadas[] = $carpetaId;

            $carpeta = DocumentoCarpeta::query()
                ->whereKey($carpetaId)
                ->where('propietario_id', auth()->id())
                ->first();

            abort_unless($carpeta, 403);
            $carpetaId = $carpeta->carpeta_padre_id === null
                ? null
                : (int) $carpeta->carpeta_padre_id;
        }
    }

    /** Mantiene protegida también la tabla interna de Filament. */
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(function (Builder $query): Builder {
                $this->validarCarpetaPropia($this->carpetaId);

                return $query
                    ->where('subido_por', auth()->id())
                    ->where('carpeta_id', $this->carpetaId);
            });
    }

    public function carpetasVisibles()
    {
        $this->validarCarpetaPropia($this->carpetaId);

        return DocumentoCarpeta::query()
            ->where('propietario_id', auth()->id())
            ->where('carpeta_padre_id', $this->carpetaId)
            ->orderBy('nombre')
            ->get();
    }

    public function rutaCarpetas(): array
    {
        $this->validarCarpetaPropia($this->carpetaId);

        $ruta = [];
        $actual = $this->carpetaId;

        while ($actual !== null) {
            $carpeta = DocumentoCarpeta::query()
                ->whereKey($actual)
                ->where('propietario_id', auth()->id())
                ->firstOrFail();

            array_unshift($ruta, $carpeta);
            $actual = $carpeta->carpeta_padre_id === null
                ? null
                : (int) $carpeta->carpeta_padre_id;
        }

        return $ruta;
    }

    public function urlCarpeta(?int $id = null): string
    {
        return DocumentoResource::getUrl(
            'index',
            $id === null ? [] : ['carpeta' => $id]
        );
    }


    public function cambiarVisibilidadAction(): Action
    {
        return Action::make('cambiarVisibilidad')
            ->label('Cambiar visibilidad')
            ->modalHeading('Cambiar acceso al documento')
            ->modalDescription(
                '¿Quieres cambiar la visibilidad de este documento? '
                    . 'Al compartirlo, todos los usuarios autenticados '
                    . 'de la intranet podrán verlo y descargarlo.'
            )
            ->modalSubmitActionLabel('Confirmar cambio')
            ->modalSubmitAction(fn(Action $action) => $action
                ->color(Color::hex('#176B45'))
                ->extraAttributes([
                    'style' => 'background-color: #176B45 !important; color: white !important;',
                ]))
            ->requiresConfirmation()
            ->action(function (array $arguments): void {

                $documentoId = (int) ($arguments['documento'] ?? 0);

                $documento = Documento::query()
                    ->whereKey($documentoId)
                    ->where('subido_por', auth()->id())
                    ->firstOrFail();

                $documento->es_publico = !$documento->es_publico;
                $documento->save();

                Notification::make()
                    ->title(
                        $documento->es_publico
                            ? 'Documento compartido con todos'
                            : 'Acceso general desactivado'
                    )
                    ->success()
                    ->send();
            });
    }

    public function elementosExplorador(): LengthAwarePaginator
    {
        $viendoCompartidos = $this->vista === 'compartidos';

        if (!$viendoCompartidos) {
            $this->validarCarpetaPropia($this->carpetaId);
        }

        $buscar = trim($this->buscar);

        // CARPETAS: solo aparecen en Mis archivos
        $carpetas = $viendoCompartidos
            ? collect()
            : DocumentoCarpeta::query()
            ->where('propietario_id', auth()->id())
            ->where('carpeta_padre_id', $this->carpetaId)
            ->when(
                $buscar !== '',
                fn($q) => $q->where(
                    'nombre',
                    'like',
                    "%{$buscar}%"
                )
            )
            ->get()
            ->map(fn($carpeta) => [
                'id' => $carpeta->id,
                'nombre' => $carpeta->nombre,
                'tipo' => 'carpeta',
                'extension' => null,
                'usuario' => null,
                'fecha' => $carpeta->created_at,
                'es_publico' => false,
                'es_propietario' => true,
            ]);


        // DOCUMENTOS: propios o compartidos
        $documentos = Documento::query()
            ->with('usuario')
            ->when(
                $viendoCompartidos,

                // Documentos públicos o compartidos individualmente
                fn($q) => $q
                    ->where('subido_por', '!=', auth()->id())
                    ->where(function ($consulta) {
                        $consulta
                            ->where('es_publico', true)
                            ->orWhereHas(
                                'usuariosCompartidos',
                                fn($usuarios) => $usuarios
                                    ->whereKey(auth()->id())
                            );
                    }),

                // Documentos propios de la carpeta actual
                fn($q) => $q
                    ->where('subido_por', auth()->id())
                    ->where('carpeta_id', $this->carpetaId)
            )
            ->when(
                $buscar !== '',
                fn($q) => $q->where(
                    'nombre_original',
                    'like',
                    "%{$buscar}%"
                )
            )
            ->get()
            ->map(function ($documento) {

                $usuario = $documento->usuario;

                return [
                    'id' => $documento->id,
                    'nombre' => $documento->nombre_original,
                    'tipo' => 'archivo',
                    'es_publico' => (bool) $documento->es_publico,
                    'es_propietario' =>
                    (int) $documento->subido_por === (int) auth()->id(),
                    'extension' => strtolower(
                        pathinfo(
                            $documento->nombre_original ?? '',
                            PATHINFO_EXTENSION
                        )
                    ),
                    'usuario' => $usuario
                        ? trim(
                            ($usuario->name ?? '') . ' ' .
                                ($usuario->apellido ?? '')
                        )
                        : 'Sin usuario',
                    'fecha' => $documento->created_at,
                    'es_publico' => (bool) $documento->es_publico,
                    'es_propietario' =>
                    (int) $documento->subido_por === (int) auth()->id(),
                ];
            });

        // UNIR CARPETAS Y DOCUMENTOS
        $elementos = $carpetas
            ->concat($documentos)
            ->sort(function ($a, $b) {

                if ($a['tipo'] !== $b['tipo']) {
                    return $a['tipo'] === 'carpeta' ? -1 : 1;
                }

                return strnatcasecmp(
                    $a['nombre'],
                    $b['nombre']
                );
            })
            ->values();

        // PAGINACIÓN
        $porPagina = 20;

        $pagina = max(
            1,
            LengthAwarePaginator::resolveCurrentPage('pagina')
        );

        $paginador = new LengthAwarePaginator(
            $elementos->forPage($pagina, $porPagina)->values(),
            $elementos->count(),
            $porPagina,
            $pagina,
            [
                'path' => request()->url(),
                'pageName' => 'pagina',
            ]
        );

        $paginador->appends(
            array_filter([
                'carpeta' => $viendoCompartidos ? null : $this->carpetaId,
                'buscar' => $this->buscar,
                'vista' => $this->vista,
            ], fn($valor) => $valor !== null && $valor !== '')
        );

        return $paginador;
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('nuevaCarpeta')
                    ->label('Nueva carpeta')
                    ->icon('heroicon-o-folder-plus')
                    ->color('warning')
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre de la carpeta')
                            ->required()
                            ->maxLength(255),
                        Select::make('carpeta_padre_id')
                            ->label('Crear dentro de')
                            ->placeholder('Carpeta principal')
                            ->options(fn() => DocumentoCarpeta::query()
                                ->where('propietario_id', auth()->id())
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id')
                                ->all())
                            ->default(fn() => $this->carpetaId)
                            ->searchable(),
                    ])
                    ->modalHeading('Crear nueva carpeta')
                    ->modalSubmitActionLabel('Crear carpeta')
                    ->modalSubmitAction(fn(Action $action) => $action
                        ->color(Color::hex('#176B45'))
                        ->extraAttributes([
                            'style' => 'background-color: #176B45 !important; color: white !important;',
                        ]))
                    ->action(function (array $data): void {
                        $padreId = filled($data['carpeta_padre_id'] ?? null)
                            ? (int) $data['carpeta_padre_id']
                            : null;
                        $this->validarCarpetaPropia($padreId);

                        DocumentoCarpeta::create([
                            'nombre' => $data['nombre'],
                            'carpeta_padre_id' => $padreId,
                            'propietario_id' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Carpeta privada creada correctamente')
                            ->success()
                            ->send();
                    }),

                Action::make('subirDocumentos')
                    ->label('Subir archivos')
                    ->icon('heroicon-o-document-arrow-up')
                    ->modalHeading('Subir documentos')
                    ->modalSubmitActionLabel('Subir documentos')
                    ->modalSubmitAction(fn(Action $action) => $action
                        ->extraAttributes([
                            'style' => 'background-color: #176B45 !important; color: white !important;',
                        ]))
                    ->schema([
                        Select::make('carpeta_id')
                            ->label('Carpeta de destino')
                            ->options(fn() => DocumentoCarpeta::query()
                                ->where('propietario_id', auth()->id())
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id')
                                ->all())
                            ->default(fn() => $this->carpetaId)
                            ->searchable()
                            ->required(),
                        FileUpload::make('archivos')
                            ->label('Seleccionar documentos')
                            ->disk('documentos_privados')
                            ->visibility('private')
                            ->multiple()
                            ->maxFiles(20)
                            ->storeFiles(false)
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->maxSize(10240)
                            ->previewable(false)
                            ->required(),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->action(function (array $data): void {
                        $carpetaId = filled($data['carpeta_id'] ?? null)
                            ? (int) $data['carpeta_id']
                            : null;
                        abort_if($carpetaId === null, 422, 'Selecciona una carpeta de destino.');
                        $this->validarCarpetaPropia($carpetaId);

                        $archivos = $data['archivos'] ?? [];
                        $rutasGuardadas = [];

                        try {
                            DB::transaction(function () use ($archivos, $data, $carpetaId, &$rutasGuardadas): void {
                                foreach ($archivos as $archivo) {
                                    $nombreOriginal = $archivo->getClientOriginalName();
                                    $extension = strtolower($archivo->getClientOriginalExtension());
                                    $mime = $archivo->getMimeType();
                                    $tamano = $archivo->getSize();

                                    $ruta = $archivo->store('archivos', 'documentos_privados');
                                    if (!$ruta) {
                                        throw new \RuntimeException('No se pudo guardar un archivo.');
                                    }
                                    $rutasGuardadas[] = $ruta;

                                    Documento::create([
                                        'carpeta_id' => $carpetaId,
                                        'nombre_original' => $nombreOriginal,
                                        'nombre_almacenado' => basename($ruta),
                                        'ruta_archivo' => $ruta,
                                        'disco' => 'documentos_privados',
                                        'extension' => $extension,
                                        'tipo_mime' => $mime,
                                        'tamano' => $tamano,
                                        'descripcion' => $data['descripcion'] ?? null,
                                        'subido_por' => auth()->id(),
                                    ]);
                                }
                            });
                        } catch (\Throwable $e) {
                            Storage::disk('documentos_privados')->delete($rutasGuardadas);
                            throw $e;
                        }

                        Notification::make()
                            ->title('Documentos subidos correctamente')
                            ->body('Se subieron ' . count($archivos) . ' archivo(s).')
                            ->success()
                            ->send();
                    }),
            ])
                ->label('Crear o cargar')
                ->icon('heroicon-o-plus')
                ->button()
                ->extraAttributes(['class' => 'prz-crear-cargar']),
        ];
    }


    public function compartirUsuariosAction(): Action
    {
        return Action::make('compartirUsuarios')
            ->label('Compartir con usuarios')
            ->modalHeading('Compartir documento')
            ->modalDescription(
                'Selecciona los usuarios que podrán ver y descargar '
                    . 'este documento. Puedes modificar estos permisos después.'
            )
            ->modalSubmitActionLabel('Guardar permisos')
            ->modalWidth('lg')
            ->color(Color::hex('#176B45'))

            // Cargar los usuarios que ya tienen permiso.
            ->fillForm(function (array $arguments): array {

                $documento = Documento::query()
                    ->whereKey((int) ($arguments['documento'] ?? 0))
                    ->where('subido_por', auth()->id())
                    ->firstOrFail();

                return [
                    'usuarios' => $documento
                        ->usuariosCompartidos()
                        ->pluck('users.id')
                        ->all(),
                ];
            })

            ->schema([
                Select::make('usuarios')
                    ->label('Usuarios autorizados')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(
                        fn(): array =>
                        User::query()
                            ->where('activo', true)
                            ->whereKeyNot(auth()->id())
                            ->orderBy('name')
                            ->get()
                            ->mapWithKeys(fn(User $user) => [
                                $user->id => trim(
                                    $user->name . ' ' . $user->apellido
                                ) . ' (' . $user->email . ')',
                            ])
                            ->all()
                    )
                    ->helperText(
                        'Selecciona uno o varios usuarios. '
                            . 'Si quitas un usuario y guardas, perderá el acceso. '
                            . 'Si dejas la selección vacía, se retirarán todos '
                            . 'los permisos individuales.'
                    ),
            ])

            ->action(function (
                array $data,
                array $arguments
            ): void {

                // Revalidar propiedad al guardar.
                $documento = Documento::query()
                    ->whereKey((int) ($arguments['documento'] ?? 0))
                    ->where('subido_por', auth()->id())
                    ->firstOrFail();

                // Nunca confiar solamente en las opciones del Select.
                $solicitados = collect($data['usuarios'] ?? [])
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values();

                $validos = User::query()
                    ->where('activo', true)
                    ->whereKeyNot(auth()->id())
                    ->whereIn('id', $solicitados->all())
                    ->pluck('id');

                abort_unless(
                    $validos->count() === $solicitados->count(),
                    422,
                    'La selección contiene usuarios no autorizados.'
                );

                // Agregar nuevos y quitar los desmarcados.
                $documento->usuariosCompartidos()
                    ->sync($validos->all());

                Notification::make()
                    ->title('Permisos actualizados correctamente')
                    ->body(
                        'Usuarios autorizados: ' . $validos->count()
                    )
                    ->success()
                    ->send();
            });
    }
}

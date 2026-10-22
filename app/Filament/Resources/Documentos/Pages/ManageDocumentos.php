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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ManageDocumentos extends ManageRecords
{
    protected static string $resource = DocumentoResource::class;

    // Vista personalizada del explorador
    protected string $view =
    'filament.resources.documentos.pages.explorador';

    // Carpeta seleccionada desde la URL.
    // NULL significa carpeta principal.
    #[Url(as: 'carpeta')]
    public ?int $carpetaId = null;

    #[Url(as: 'buscar')]
    public string $buscar = '';

    // Mostrar solamente documentos de la carpeta actual
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->where(
                    'carpeta_id',
                    $this->carpetaId
                )
            );
    }

    // Carpetas que pertenecen a la ubicación actual
    public function carpetasVisibles()
    {
        return DocumentoCarpeta::query()
            ->where('carpeta_padre_id', $this->carpetaId)
            ->orderBy('nombre')
            ->get();
    }

    // Construir la ruta de navegación
    public function rutaCarpetas(): array
    {
        $ruta = [];
        $actual = $this->carpetaId;
        $visitadas = [];

        while ($actual !== null) {

            if (in_array($actual, $visitadas, true)) {
                break;
            }

            $visitadas[] = $actual;

            $carpeta = DocumentoCarpeta::find($actual);

            if (!$carpeta) {
                break;
            }

            array_unshift($ruta, $carpeta);

            $actual = $carpeta->carpeta_padre_id;
        }

        return $ruta;
    }

    // Enlaces para navegar
    public function urlCarpeta(?int $id = null): string
    {
        return DocumentoResource::getUrl(
            'index',
            $id === null ? [] : ['carpeta' => $id]
        );
    }


    public function elementosExplorador(): LengthAwarePaginator
    {
        $buscar = trim($this->buscar);

        // 1. Obtener las carpetas de la ubicación actual
        $carpetas = DocumentoCarpeta::query()
            ->where('carpeta_padre_id', $this->carpetaId)
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->get()
            ->map(function ($carpeta) {

                return [
                    'id' => $carpeta->id,
                    'nombre' => $carpeta->nombre,
                    'tipo' => 'carpeta',
                    'extension' => null,
                    'usuario' => null,
                    'fecha' => $carpeta->created_at,
                ];
            });

        // 2. Obtener los documentos de la ubicación actual
        $documentos = Documento::query()
            ->with('usuario')
            ->where('carpeta_id', $this->carpetaId)
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(
                    'nombre_original',
                    'like',
                    "%{$buscar}%"
                );
            })
            ->get()
            ->map(function ($documento) {

                $usuario = $documento->usuario;

                $nombreUsuario = $usuario
                    ? trim(
                        ($usuario->name ?? '') . ' ' .
                            ($usuario->apellido ?? '')
                    )
                    : 'Sin usuario';

                return [
                    'id' => $documento->id,
                    'nombre' => $documento->nombre_original,
                    'tipo' => 'archivo',
                    'extension' => strtolower(
                        $documento->extension ?? ''
                    ),
                    'usuario' => $nombreUsuario,
                    'fecha' => $documento->created_at,
                ];
            });

        // 3. Unir ambos grupos en una única lista
        $elementos = $carpetas
            ->concat($documentos)
            ->sort(function ($a, $b) {

                // Primero las carpetas
                if ($a['tipo'] !== $b['tipo']) {
                    return $a['tipo'] === 'carpeta' ? -1 : 1;
                }

                // Después, ordenar por nombre
                return strnatcasecmp(
                    $a['nombre'],
                    $b['nombre']
                );
            })
            ->values();

        // 4. Paginar el resultado combinado
        $porPagina = 20;

        $pagina = max(
            1,
            LengthAwarePaginator::resolveCurrentPage('pagina')
        );

        $total = $elementos->count();

        $resultado = $elementos
            ->forPage($pagina, $porPagina)
            ->values();

        $paginador = new LengthAwarePaginator(
            $resultado,
            $total,
            $porPagina,
            $pagina,
            [
                'path' => request()->url(),
                'pageName' => 'pagina',
            ]
        );

        // Conservar carpeta y búsqueda al cambiar página
        $paginador->appends(
            array_filter([
                'carpeta' => $this->carpetaId,
                'buscar' => $this->buscar,
            ], fn($valor) => $valor !== null && $valor !== '')
        );

        return $paginador;
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([

                // NUEVA CARPETA
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
                            ->options(
                                fn() => DocumentoCarpeta::query()
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                                    ->all()
                            )
                            ->default(fn() => $this->carpetaId)
                            ->searchable(),

                    ])

                    ->modalHeading('Crear nueva carpeta')
                    ->modalSubmitActionLabel('Crear carpeta')

                    ->modalSubmitAction(
                        fn(Action $action) => $action
                            ->color(Color::hex('#176B45'))
                            ->extraAttributes([
                                'style' =>
                                'background-color: #176B45 !important; color: white !important;',
                            ])
                    )

                    ->action(function (array $data): void {

                        DocumentoCarpeta::create([
                            'nombre' => $data['nombre'],
                            'carpeta_padre_id' =>
                            $data['carpeta_padre_id'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Carpeta creada correctamente')
                            ->success()
                            ->send();
                    }),

                // SUBIR DOCUMENTOS
                Action::make('subirDocumentos')
                    ->label('Subir archivos')
                    ->icon('heroicon-o-document-arrow-up')

                    ->modalHeading('Subir documentos')
                    ->modalSubmitActionLabel('Subir documentos')

                    ->modalSubmitAction(
                        fn(Action $action) => $action
                            ->extraAttributes([
                                'style' =>
                                'background-color: #176B45 !important; color: white !important;',
                            ])
                    )

                    ->schema([

                        Select::make('carpeta_id')
                            ->label('Carpeta de destino')
                            ->options(
                                fn() => DocumentoCarpeta::query()
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                                    ->all()
                            )
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

                        $archivos = $data['archivos'] ?? [];
                        $rutasGuardadas = [];

                        try {

                            DB::transaction(function () use (
                                $archivos,
                                $data,
                                &$rutasGuardadas
                            ): void {

                                foreach ($archivos as $archivo) {

                                    $ruta = $archivo->store(
                                        'archivos',
                                        'documentos_privados'
                                    );

                                    if (!$ruta) {
                                        throw new \RuntimeException(
                                            'No se pudo guardar un archivo.'
                                        );
                                    }

                                    $rutasGuardadas[] = $ruta;

                                    Documento::create([

                                        'carpeta_id' =>
                                        $data['carpeta_id'],

                                        'nombre_original' =>
                                        $archivo->getClientOriginalName(),

                                        'nombre_almacenado' =>
                                        basename($ruta),

                                        'ruta_archivo' => $ruta,

                                        'disco' => 'documentos_privados',

                                        'extension' => strtolower(
                                            pathinfo(
                                                $ruta,
                                                PATHINFO_EXTENSION
                                            )
                                        ),

                                        'tipo_mime' =>
                                        $archivo->getMimeType(),

                                        'tamano' =>
                                        $archivo->getSize(),

                                        'descripcion' =>
                                        $data['descripcion'] ?? null,

                                        'subido_por' => auth()->id(),

                                    ]);
                                }
                            });
                        } catch (\Throwable $e) {

                            Storage::disk('documentos_privados')
                                ->delete($rutasGuardadas);

                            throw $e;
                        }

                        Notification::make()
                            ->title('Documentos subidos correctamente')
                            ->body(
                                'Se subieron ' .
                                    count($archivos) .
                                    ' archivo(s).'
                            )
                            ->success()
                            ->send();
                    }),

            ])
                ->label('Crear o cargar')
                ->icon('heroicon-o-plus')
                ->button()
                ->extraAttributes([
                    'class' => 'prz-crear-cargar',
                ]),
        ];
    }
}

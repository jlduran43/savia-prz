<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Models\DocumentoCarpeta;
use App\Models\Documento;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageDocumentos extends ManageRecords
{
    protected static string $resource = DocumentoResource::class;

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
                            ->options(
                                fn() =>
                                DocumentoCarpeta::query()
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                                    ->all()
                            )
                            ->searchable(),
                    ])
                    ->modalHeading('Crear nueva carpeta')
                    ->modalSubmitActionLabel('Crear carpeta')
                    ->modalSubmitAction(
                        fn(Action $action) => $action
                            ->color(Color::hex('#176B45'))
                            ->extraAttributes([
                                'style' => 'background-color: #176B45 !important; color: white !important;',
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



                Action::make('subirDocumentos')
                    ->label('Subir archivos')
                    ->icon('heroicon-o-document-arrow-up')

                    ->modalHeading('Subir documentos')
                    ->modalSubmitActionLabel('Subir documentos')

                    ->modalSubmitAction(
                        fn(Action $action) => $action
                            ->extraAttributes([
                                'style' => 'background-color: #176B45 !important; color: white !important;',
                            ])
                    )

                    ->schema([

                        Select::make('carpeta_id')
                            ->label('Carpeta de destino')
                            ->options(
                                fn() =>
                                DocumentoCarpeta::query()
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                                    ->all()
                            )
                            ->searchable()
                            ->required(),

                        FileUpload::make('archivos')
                            ->label('Seleccionar documentos')
                            ->disk('documentos_privados')
                            ->visibility('private')

                            // Permitir varios archivos
                            ->multiple()
                            ->maxFiles(20)

                            // Los guardaremos manualmente,
                            // uno por cada registro
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

                                        'carpeta_id' => $data['carpeta_id'],

                                        'nombre_original' =>
                                        $archivo->getClientOriginalName(),

                                        'nombre_almacenado' =>
                                        basename($ruta),

                                        'ruta_archivo' => $ruta,

                                        'disco' => 'documentos_privados',

                                        'extension' =>
                                        strtolower(pathinfo(
                                            $ruta,
                                            PATHINFO_EXTENSION
                                        )),

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

                            // Si falla algún archivo, eliminar los
                            // nuevos archivos que ya se almacenaron
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

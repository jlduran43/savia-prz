<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Models\DocumentoCarpeta;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;

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

                CreateAction::make()
                    ->label('Subir archivo')
                    ->icon('heroicon-o-document-arrow-up')
                    ->modalHeading('Subir documento')
                    ->modalSubmitActionLabel('Subir documento')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['subido_por'] = auth()->id();

                        return $data;
                    }),

            ])
                ->label('Crear o cargar')
                ->icon('heroicon-o-plus')
                ->button()
                ->color(Color::hex('#176B45'))
                ->extraAttributes([
                    'style' => 'background-color: #176B45 !important; color: white !important;',
                ]),
        ];
    }
}

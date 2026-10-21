<?php

namespace App\Filament\Resources\Documentos;

use App\Filament\Resources\Documentos\Pages\ManageDocumentos;
use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'nombre_original';

    protected static ?string $modelLabel = 'documento';

    protected static ?string $pluralModelLabel = 'documentos';

    protected static ?string $navigationLabel = 'Archivos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

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

            FileUpload::make('ruta_archivo')
                ->label('Seleccionar documento')
                ->disk('documentos_privados')
                ->directory('archivos')
                ->visibility('private')
                ->storeFileNamesIn('nombre_original')
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
                ->openable(false)
                ->downloadable(false)
                ->required(),

            Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(3)
                ->maxLength(1000),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nombre_original')
                    ->label('Documento')
                    ->icon('heroicon-o-document-text')
                    ->searchable(),

                TextColumn::make('carpeta.nombre')
                    ->label('Carpeta'),

                TextColumn::make('usuario.name')
                    ->label('Subido por')
                    ->formatStateUsing(
                        fn($record) => $record->usuario
                            ? trim(
                                $record->usuario->name . ' ' .
                                    $record->usuario->apellido
                            )
                            : 'Sin usuario'
                    )
                    ->searchable(),

                TextColumn::make('extension')
                    ->label('Tipo')
                    ->badge(),

                TextColumn::make('descargar')
                    ->label('Descargar')
                    ->state(fn() => 'Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->url(
                        fn(Documento $record): string =>
                        route('documentos.descargar', [
                            'documento' => $record->id,
                        ])
                    ),

                TextColumn::make('created_at')
                    ->label('Fecha de subida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

            ])
            ->recordActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDocumentos::route('/'),
        ];
    }
}

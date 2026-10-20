<?php

namespace App\Filament\Resources\DocumentoCarpetas\Schemas;

use App\Models\DocumentoCarpeta;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DocumentoCarpetaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('nombre')
                ->label('Nombre de la carpeta')
                ->required()
                ->maxLength(255),

            Select::make('carpeta_padre_id')
                ->label('Ubicación')
                ->placeholder('Carpeta principal')
                ->options(fn (?DocumentoCarpeta $record) =>
                    DocumentoCarpeta::query()
                        ->whereNull('carpeta_padre_id')
                        ->when(
                            $record,
                            fn ($query) => $query->whereKeyNot($record->id)
                        )
                        ->orderBy('nombre')
                        ->pluck('nombre', 'id')
                        ->all()
                )
                ->searchable(),

            Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(3)
                ->maxLength(1000),

        ]);
    }
}

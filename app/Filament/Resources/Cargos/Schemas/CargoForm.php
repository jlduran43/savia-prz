<?php

namespace App\Filament\Resources\Cargos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class CargoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('departamento_id')
                    ->label('Departamento')
                    ->relationship('departamento', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('nombre')
                    ->label('Cargo')
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}

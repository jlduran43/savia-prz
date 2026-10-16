<?php

namespace App\Filament\Resources\Departamentos\Schemas;

use App\Filament\Resources\Departamentos\DepartamentoResource;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;

class DepartamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->disabledOn('view'),

                Actions::make([
                    Action::make('volver')
                        ->label('Volver')
                        ->icon('heroicon-o-arrow-left')
                        ->color('gray')
                        ->url(DepartamentoResource::getUrl('index')),
                ])
                    ->visibleOn('view')
                    ->columnSpanFull(),
            ]);
    }
}
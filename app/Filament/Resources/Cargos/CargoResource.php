<?php

namespace App\Filament\Resources\Cargos;

use App\Filament\Resources\Cargos\Pages\CreateCargo;
use App\Filament\Resources\Cargos\Pages\EditCargo;
use App\Filament\Resources\Cargos\Pages\ListCargos;
use App\Filament\Resources\Cargos\Pages\ViewCargo;
use App\Filament\Resources\Cargos\Schemas\CargoForm;
use App\Filament\Resources\Cargos\Schemas\CargoInfolist;
use App\Filament\Resources\Cargos\Tables\CargosTable;
use App\Models\Cargo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CargoResource extends Resource
{
    protected static ?string $model = Cargo::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'no';

    public static function form(Schema $schema): Schema
    {
        return CargoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CargoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CargosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCargos::route('/'),
            'create' => CreateCargo::route('/create'),
            'view' => ViewCargo::route('/{record}'),
            'edit' => EditCargo::route('/{record}/edit'),
        ];
    }
}

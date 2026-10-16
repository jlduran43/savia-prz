<?php

namespace App\Filament\Resources\TipoVehiculos;

use App\Filament\Resources\TipoVehiculos\Pages\CreateTipoVehiculo;
use App\Filament\Resources\TipoVehiculos\Pages\EditTipoVehiculo;
use App\Filament\Resources\TipoVehiculos\Pages\ListTipoVehiculos;
use App\Filament\Resources\TipoVehiculos\Schemas\TipoVehiculoForm;
use App\Filament\Resources\TipoVehiculos\Tables\TipoVehiculosTable;
use App\Models\TipoVehiculo;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TipoVehiculoResource extends Resource
{
    protected static ?string $model = TipoVehiculo::class;

    protected static ?string $navigationLabel = 'Tipos de vehículo';

    protected static ?string $modelLabel = 'Tipo de vehículo';

    protected static ?string $pluralModelLabel = 'Tipos de vehículo';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TipoVehiculoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoVehiculosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTipoVehiculos::route('/'),
            'create' => CreateTipoVehiculo::route('/create'),
            'edit' => EditTipoVehiculo::route('/{record}/edit'),
        ];
    }
}
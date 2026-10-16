<?php

namespace App\Filament\Resources\IngresoVehiculos;

use App\Filament\Resources\IngresoVehiculos\Pages\CreateIngresoVehiculos;
use App\Filament\Resources\IngresoVehiculos\Pages\ListIngresoVehiculos;
use App\Filament\Resources\IngresoVehiculos\Schemas\IngresoVehiculoForm;
use App\Filament\Resources\IngresoVehiculos\Schemas\IngresoVehiculoInfolist;
use App\Filament\Resources\IngresoVehiculos\Tables\IngresoVehiculoTable;
use App\Models\IngresoVehiculo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IngresoVehiculoResource extends Resource
{
    protected static ?string $model = IngresoVehiculo::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Ingreso de Vehículos';

    protected static ?string $modelLabel = 'Ingreso de vehículo';

    protected static ?string $pluralModelLabel = 'Ingresos de Vehículos';

    protected static string|\UnitEnum|null $navigationGroup = 'Registros';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'ingresos-vehiculos';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return IngresoVehiculoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IngresoVehiculoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngresoVehiculoTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIngresoVehiculos::route('/'),
            'create' => CreateIngresoVehiculos::route('/create'),
            //'view' => ViewIngresoVehiculos::route('/{record}'),
            //'edit' => EditIngresoVehiculos::route('/{record}/edit'),
        ];
    }
}
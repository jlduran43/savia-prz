<?php

namespace App\Filament\Resources\MedioPagos;

use App\Filament\Resources\MedioPagos\Pages\CreateMedioPago;
use App\Filament\Resources\MedioPagos\Pages\EditMedioPago;
use App\Filament\Resources\MedioPagos\Pages\ListMedioPagos;
use App\Filament\Resources\MedioPagos\Schemas\MedioPagoForm;
use App\Filament\Resources\MedioPagos\Tables\MediosPagoTable;
use App\Models\MedioPago;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MedioPagoResource extends Resource
{
    protected static ?string $model = MedioPago::class;

    protected static ?string $navigationLabel = 'Medios de pago';

    protected static ?string $modelLabel = 'Medio de pago';

    protected static ?string $pluralModelLabel = 'Medios de pago';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MedioPagoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MediosPagoTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedioPagos::route('/'),
            'create' => CreateMedioPago::route('/create'),
            'edit' => EditMedioPago::route('/{record}/edit'),
        ];
    }
}
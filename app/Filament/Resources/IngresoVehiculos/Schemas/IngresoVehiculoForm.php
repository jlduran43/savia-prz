<?php

namespace App\Filament\Resources\IngresoVehiculos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class IngresoVehiculoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Hidden::make('tipo_movimiento')
                    ->default('Ingreso'),

                Hidden::make('centro_ingreso')
                    ->default('Arqueo Acceso'),

                Select::make('condicion_pago')
                    ->label('Condición de pago')
                    ->options([
                        'PAGA' => 'Paga',
                        'LIBERADO' => 'Liberado',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'LIBERADO') {
                            $set('medio_pago', null);
                            $set('monto', null);
                            $set('patente', null);
                            $set('responsable', null);
                            $set('fecha', null);
                            $set('hora_inicio', null);
                            $set('visitante', null);
                            $set('motivo_visita', null);
                        }
                    })
                    ->native(false),

                TextInput::make('responsable')
                    ->label('Responsable')
                    ->required()
                    ->maxLength(150),

                TextInput::make('visitante')
                    ->label('Visitante / Conductor')
                    ->required(fn($get) => $get('condicion_pago') === 'LIBERADO')
                    ->visible(fn($get) => $get('condicion_pago') === 'LIBERADO')
                    ->maxLength(150),

                TextInput::make('patente')
                    ->label('Patente')
                    ->visible(fn($get) => $get('condicion_pago') === 'LIBERADO')
                    ->required(fn($get) => $get('condicion_pago') === 'LIBERADO')
                    ->maxLength(6)
                    ->extraInputAttributes([
                        'style' => 'text-transform: uppercase;',
                    ])
                    ->dehydrateStateUsing(function ($state) {
                        if (!$state) {
                            return null;
                        }

                        return strtoupper(
                            preg_replace('/[^A-Za-z0-9]/', '', $state)
                        );
                    })
                    ->regex('/^([A-Z]{2}[0-9]{4}|[A-Z]{4}[0-9]{2}|[A-Z]{5}[0-9])$/')
                    ->validationMessages([
                        'regex' => 'Ingrese una patente chilena válida. Ejemplo: AB1234 o ABCD12.',
                    ]),

                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->visible(fn(Get $get) => filled($get('condicion_pago')))
                    ->required(fn(Get $get) => filled($get('condicion_pago'))),
                
                Textarea::make('motivo_visita')
                    ->label('Motivo de visita')
                    ->required(fn($get) => $get('condicion_pago') === 'LIBERADO')
                    ->visible(fn($get) => $get('condicion_pago') === 'LIBERADO')
                    ->rows(3)
                    ->maxLength(500),

                Select::make('tipo_vehiculo_id')
                    ->label('Tipo de vehículo')
                    ->relationship(
                        name: 'tipoVehiculo',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn($query) => $query
                            ->where('activo', true)
                            ->orderBy('nombre')
                    )
                    ->placeholder('Seleccione una opción')
                    ->preload()
                    ->live()
                    ->visible(fn(Get $get) => $get('condicion_pago') === 'PAGA')
                    ->required(fn(Get $get) => $get('condicion_pago') === 'PAGA'),

                Select::make('medio_pago_id')
                    ->label('Medio de pago')
                    ->relationship(
                        name: 'medioPago',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn($query) => $query
                            ->where('activo', true)
                            ->orderBy('nombre')
                    )
                    ->visible(fn(Get $get) => $get('condicion_pago') === 'PAGA')
                    ->required(fn(Get $get) => $get('condicion_pago') === 'PAGA')
                    ->preload(),

                TextInput::make('monto')
                    ->label('Monto')
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0)
                    ->required(fn($get) => $get('condicion_pago') === 'PAGA')
                    ->visible(fn($get) => $get('condicion_pago') === 'PAGA'),
            ]);
    }
}

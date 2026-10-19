<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Models\Cargo;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Support\RutChileno;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nombres')
                    ->required(),

                TextInput::make('apellido')
                    ->label('Apellidos')
                    ->required(),

                TextInput::make('rut')
                    ->label('RUT')
                    ->required()
                    ->placeholder('12.345.678-5')
                    ->maxLength(12)
                    ->unique(
                        table: 'users',
                        column: 'rut',
                        ignoreRecord: true,
                        modifyRuleUsing: fn($rule) => $rule
                            ->whereNotNull('rut')
                    )
                    ->validationMessages([
                        'required' => 'El RUT es obligatorio.',
                        'unique' => 'El RUT ingresado ya se encuentra registrado.',
                    ])

                    // Validar al salir del campo
                    ->live(onBlur: true)

                    // Formatear automáticamente
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        if (filled($state)) {
                            $set('rut', RutChileno::formatear($state));
                        }
                    })

                    // Ícono según resultado
                    ->suffixIcon(function (?string $state) {
                        if (blank($state)) {
                            return null;
                        }

                        return RutChileno::esValido($state)
                            ? 'heroicon-o-check-circle'
                            : 'heroicon-o-x-circle';
                    })

                    // Color del ícono
                    ->suffixIconColor(function (?string $state) {
                        if (blank($state)) {
                            return 'gray';
                        }

                        return RutChileno::esValido($state)
                            ? 'success'
                            : 'danger';
                    })

                    // Mensaje visual
                    ->helperText(function (?string $state) {
                        if (blank($state)) {
                            return 'Ingrese un RUT chileno.';
                        }

                        return RutChileno::esValido($state)
                            ? '✓ RUT válido'
                            : '✗ RUT inválido. Verifique el dígito verificador.';
                    })

                    // Validación obligatoria antes de guardar
                    ->rules([
                        fn() => function (
                            string $attribute,
                            $value,
                            \Closure $fail
                        ) {
                            if (!RutChileno::esValido($value)) {
                                $fail('El RUT ingresado no es válido.');
                            }
                        },
                    ])

                    // Guardar con formato estándar
                    ->dehydrateStateUsing(
                        fn(?string $state) => RutChileno::formatear($state)
                    ),


                TextInput::make('email')
                    ->label('Correo')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: 'users',
                        column: 'email',
                        ignoreRecord: true
                    )
                    ->validationMessages([
                        'required' => 'El correo electrónico es obligatorio.',
                        'email' => 'Ingrese un correo electrónico válido.',
                        'unique' => 'El correo electrónico ingresado ya se encuentra registrado.',
                        'max' => 'El correo electrónico no puede superar los 255 caracteres.',
                    ]),

                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(
                        fn(string $operation): bool =>
                        $operation === 'create'
                    )
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => bcrypt($state)),

                // Departamento
                Select::make('departamento_id')
                    ->label('Departamento')
                    ->relationship('departamento', 'nombre')
                    ->placeholder('Seleccione un departamento')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('cargo_id', null);
                        $set('jefe_id', null);
                    }),

                // Cargo filtrado por departamento
                Select::make('cargo_id')
                    ->label('Cargo')
                    ->options(function (Get $get) {
                        return Cargo::query()
                            ->where(
                                'departamento_id',
                                $get('departamento_id')
                            )
                            ->where('activo', true)
                            ->pluck('nombre', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->disabled(
                        fn(Get $get) =>
                        blank($get('departamento_id'))
                    )
                    ->required(),

                // Jefatura directa
                Select::make('jefe_id')
                    ->label('Jefatura directa')
                    ->placeholder('Seleccione una jefatura')
                    ->nullable()
                    ->required(false)
                    ->options(function (Get $get, ?User $record) {

                        $departamentoId = $get('departamento_id');

                        if (blank($departamentoId)) {
                            return [];
                        }

                        return User::query()
                            ->where('activo', true)

                            // Solo trabajadores del departamento seleccionado
                            ->where('departamento_id', $departamentoId)

                            // Excluir al mismo usuario que estamos editando
                            ->when(
                                $record,
                                fn($query) => $query->where('id', '!=', $record->id)
                            )

                            ->orderBy('name')
                            ->get()
                            ->mapWithKeys(fn($user) => [
                                $user->id => trim(
                                    $user->name . ' ' . ($user->apellido ?? '')
                                ),
                            ])
                            ->all();
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->disabled(
                        fn(Get $get) => blank($get('departamento_id'))
                    )
                    ->helperText(
                        'Seleccione una jefatura del departamento correspondiente.'
                    ),
            ]);
    }
}

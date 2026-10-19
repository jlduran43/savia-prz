<?php

namespace App\Filament\Pages\Auth;

use App\Models\Cargo;
use App\Models\User;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Validation\ValidationException;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->label('Nombres')
                ->required()
                ->maxLength(255),

            TextInput::make('apellido')
                ->label('Apellidos')
                ->required()
                ->maxLength(255),

            TextInput::make('rut')
                ->label('RUT')
                ->required()
                ->maxLength(12)
                ->unique(
                    table: 'users',
                    column: 'rut',
                    ignoreRecord: true
                ),

            TextInput::make('email')
                ->label('Correo electrónico')
                ->email()
                ->required()
                ->unique(
                    table: 'users',
                    column: 'email',
                    ignoreRecord: true
                ),

            Select::make('departamento_id')
                ->label('Departamento')
                ->relationship('departamento', 'nombre')
                ->searchable()
                ->preload(),

            // CARGO: seleccionar o crear desde el buscador
            Select::make('cargo_id')
                ->label('Cargo')
                ->required()
                ->relationship('cargo', 'nombre')
                ->searchable()
                ->preload()
                ->native(false)
                ->live()

                // Solo administradores pueden crear cargos
                ->createOptionForm(
                    fn(): array =>
                    auth()->user()?->rol === 'ADMIN'
                        ? [
                            TextInput::make('nombre')
                                ->label('Nombre del nuevo cargo')
                                ->required()
                                ->maxLength(255)
                                ->unique(
                                    table: 'cargos',
                                    column: 'nombre'
                                )
                                ->validationMessages([
                                    'required' => 'Debe ingresar el nombre del cargo.',
                                    'unique' => 'Este cargo ya se encuentra registrado.',
                                ]),
                        ]
                        : []
                )

                ->createOptionUsing(function (array $data): int {

                    abort_unless(
                        auth()->user()?->rol === 'ADMIN',
                        403,
                        'No tiene permiso para crear cargos.'
                    );

                    $nombre = trim(
                        preg_replace('/\s+/u', ' ', $data['nombre'])
                    );

                    $cargo = Cargo::firstOrCreate(
                        ['nombre' => $nombre],
                        ['activo' => true]
                    );

                    return $cargo->getKey();
                }),

            Select::make('jefe_id')
                ->label('Jefatura directa')
                ->options(
                    fn() => User::query()
                        ->whereKeyNot(auth()->id())
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn($user) => [
                            $user->id => trim(
                                $user->name . ' ' . $user->apellido
                            ),
                        ])
                        ->all()
                )
                ->searchable(),

            $this->getPasswordFormComponent(),

        ]);
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar cambios')
            ->color('success')
            ->extraAttributes([
                'style' =>
                'background-color: #176B45 !important; color: #ffffff !important;',
            ]);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Volver')
            ->color('gray');
    }
}

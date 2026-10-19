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
                ->relationship(
                    name: 'departamento',
                    titleAttribute: 'nombre',
                    modifyQueryUsing: fn($query) => $query
                        ->where('activo', true)
                )
                ->searchable()
                ->preload()
                ->native(false),

            // CARGO: seleccionar o crear desde el buscador


            Select::make('cargo_id')
                ->label('Cargo')
                ->required()
                ->searchable()
                ->preload()
                ->native(false)
                ->live()

                // Mostrar cargos existentes
                ->options(
                    fn(): array => Cargo::query()
                        ->where('activo', true)
                        ->orderBy('nombre')
                        ->pluck('nombre', 'id')
                        ->all()
                )

                // Buscar y ofrecer crear un cargo nuevo
                ->getSearchResultsUsing(function (string $search): array {

                    $nombre = trim(
                        preg_replace('/\s+/u', ' ', $search)
                    );

                    $resultados = Cargo::query()
                        ->where('activo', true)
                        ->where('nombre', 'like', '%' . $nombre . '%')
                        ->orderBy('nombre')
                        ->limit(30)
                        ->pluck('nombre', 'id')
                        ->all();

                    if (
                        $nombre !== '' &&
                        mb_strlen($nombre) <= 255 &&
                        ! Cargo::query()
                            ->whereRaw(
                                'LOWER(nombre) = ?',
                                [mb_strtolower($nombre)]
                            )
                            ->exists()
                    ) {
                        $resultados['nuevo:' . $nombre] =
                            '➕ Crear nuevo cargo: ' . $nombre;
                    }

                    return $resultados;
                })

                // Mostrar el nombre seleccionado
                ->getOptionLabelUsing(function ($value): ?string {

                    if (
                        is_string($value) &&
                        str_starts_with($value, 'nuevo:')
                    ) {
                        return 'Crear nuevo cargo: ' . substr($value, 6);
                    }

                    return Cargo::find($value)?->nombre;
                })

                // Crear el cargo automáticamente
                ->afterStateUpdated(function ($state, Set $set): void {

                    if (
                        ! is_string($state) ||
                        ! str_starts_with($state, 'nuevo:')
                    ) {
                        return;
                    }

                    $nombre = trim(
                        preg_replace(
                            '/\s+/u',
                            ' ',
                            substr($state, 6)
                        )
                    );

                    if (
                        $nombre === '' ||
                        mb_strlen($nombre) > 255
                    ) {
                        $set('cargo_id', null);

                        throw ValidationException::withMessages([
                            'data.cargo_id' =>
                            'El nombre del cargo no es válido.',
                        ]);
                    }

                    // Buscar antes de crear para evitar duplicados
                    $cargo = Cargo::query()
                        ->whereRaw(
                            'LOWER(nombre) = ?',
                            [mb_strtolower($nombre)]
                        )
                        ->first();

                    if (! $cargo) {
                        $cargo = Cargo::create([
                            'nombre' => $nombre,
                            'activo' => true,
                        ]);
                    }

                    // Reemplazar por el ID real
                    $set('cargo_id', $cargo->id);
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

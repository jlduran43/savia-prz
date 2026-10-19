<?php

namespace App\Filament\Pages\Auth;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\User;
use App\Support\RutChileno;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Illuminate\Validation\Rule;

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

            Select::make('cargo_id')
                ->label('Cargo')
                ->relationship('cargo', 'nombre')
                ->searchable()
                ->preload(),

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
                'style' => 'background-color: #176B45 !important; color: #ffffff !important;',
            ]);
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Volver')
            ->color('gray');
    }
}

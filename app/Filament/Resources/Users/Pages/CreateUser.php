<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Crear')
                ->color('success')
                ->extraAttributes([
                    'style' => 'background-color: #176b4a; color: white;',
                ]),

            $this->getCancelFormAction()
                ->label('Volver'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

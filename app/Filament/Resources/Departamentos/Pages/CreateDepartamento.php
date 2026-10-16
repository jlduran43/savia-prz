<?php

namespace App\Filament\Resources\Departamentos\Pages;

use App\Filament\Resources\Departamentos\DepartamentoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartamento extends CreateRecord
{
    protected static string $resource = DepartamentoResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->extraAttributes([
                'style' => 'background-color: #176b45 !important; color: #ffffff !important;',
            ]);
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Volver');
    }
}

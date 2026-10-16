<?php

namespace App\Filament\Resources\Departamentos\Pages;

use App\Filament\Resources\Departamentos\DepartamentoResource;
use Filament\Resources\Pages\EditRecord;

class EditDepartamento extends EditRecord
{
    protected static string $resource = DepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar cambios')
            ->color('success')
            ->extraAttributes([
                'style' => 'background-color: #176B4A !important; color: white !important;',
            ]);
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Volver')
            ->icon('heroicon-o-arrow-left')
            ->color('gray');
    }
}

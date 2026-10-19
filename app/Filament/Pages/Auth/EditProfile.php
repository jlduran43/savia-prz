<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Actions\Action;

class EditProfile extends BaseEditProfile
{
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar cambios')
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

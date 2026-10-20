<?php

namespace App\Filament\Pages;

use App\Models\DocumentoCarpeta;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class RepositorioDocumental extends Page
{
    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Repositorio documental';

    protected static ?string $title = 'Repositorio documental';

    protected string $view =
        'filament.pages.repositorio-documental';

    public ?int $carpetaActualId = null;

    public function abrirCarpeta(int $id): void
    {
        $carpeta = DocumentoCarpeta::findOrFail($id);

        if ($this->carpetaActualId !== $carpeta->carpeta_padre_id) {
            return;
        }

        $this->carpetaActualId = $carpeta->id;
    }

    public function volver(): void
    {
        if ($this->carpetaActualId === null) {
            return;
        }

        $carpeta = DocumentoCarpeta::findOrFail(
            $this->carpetaActualId
        );

        $this->carpetaActualId = $carpeta->carpeta_padre_id;
    }

    public function getCarpetasProperty()
    {
        return DocumentoCarpeta::query()
            ->where('carpeta_padre_id', $this->carpetaActualId)
            ->orderBy('nombre')
            ->get();
    }

    public function getCarpetaActualProperty()
    {
        return $this->carpetaActualId
            ? DocumentoCarpeta::find($this->carpetaActualId)
            : null;
    }
}
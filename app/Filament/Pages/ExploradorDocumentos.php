<?php

namespace App\Filament\Pages;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExploradorDocumentos extends Page
{
    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Explorador';
    protected static ?string $title = 'Explorador documental';

    protected string $view =
    'filament.pages.explorador-documentos';

    public ?int $carpetaActual = null;

    private function carpetaAutorizada(int $id): DocumentoCarpeta
    {
        return DocumentoCarpeta::query()
            ->whereKey($id)
            ->where('creado_por', auth()->id())
            ->firstOrFail();
    }

    public function abrirCarpeta(int $id): void
    {
        $this->carpetaAutorizada($id);
        $this->carpetaActual = $id;
    }

    public function volver(): void
    {
        if ($this->carpetaActual === null) {
            return;
        }

        $carpeta = $this->carpetaAutorizada(
            $this->carpetaActual
        );

        $this->carpetaActual = $carpeta->carpeta_padre_id;

        if ($this->carpetaActual !== null) {
            $this->carpetaAutorizada($this->carpetaActual);
        }
    }

    public function nuevaCarpetaAction(): Action
    {
        return Action::make('nuevaCarpeta')
            ->label('Nueva carpeta')
            ->icon('heroicon-o-folder-plus')
            ->color('success')
            ->schema([
                TextInput::make('nombre')
                    ->label('Nombre de la carpeta')
                    ->required()
                    ->maxLength(150),
            ])
            ->action(function (array $data): void {
                if ($this->carpetaActual !== null) {
                    $this->carpetaAutorizada(
                        $this->carpetaActual
                    );
                }

                DocumentoCarpeta::create([
                    'nombre' => trim($data['nombre']),
                    'carpeta_padre_id' => $this->carpetaActual,
                    'creado_por' => auth()->id(),
                ]);

                Notification::make()
                    ->title('Carpeta creada correctamente')
                    ->success()
                    ->send();
            });
    }

    public function subirDocumentoAction(): Action
    {
        return Action::make('subirDocumento')
            ->label('Subir documento')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->schema([
                FileUpload::make('archivo')
                    ->label('Seleccionar documento')
                    ->disk('documentos_privados')
                    ->directory('archivos')
                    ->visibility('private')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->maxSize(10240)
                    ->required(),
            ])
            ->action(function (array $data): void {
                if ($this->carpetaActual === null) {
                    Notification::make()
                        ->title('Primero ingresa a una carpeta')
                        ->warning()
                        ->send();

                    return;
                }

                $this->carpetaAutorizada(
                    $this->carpetaActual
                );

                $ruta = $data['archivo'];
                $disco = Storage::disk('documentos_privados');

                if (! is_string($ruta) || ! $disco->exists($ruta)) {
                    throw new \RuntimeException(
                        'El archivo no fue almacenado correctamente.'
                    );
                }

                $nombreOriginal = basename($ruta);

                Documento::create([
                    'carpeta_id' => $this->carpetaActual,
                    'nombre' => pathinfo(
                        $nombreOriginal,
                        PATHINFO_FILENAME
                    ),
                    'nombre_original' => $nombreOriginal,
                    'ruta_archivo' => $ruta,
                    'subido_por' => auth()->id(),
                ]);

                Notification::make()
                    ->title('Documento subido correctamente')
                    ->success()
                    ->send();
            });
    }

    protected function getViewData(): array
    {
        $usuarioId = auth()->id();

        $carpeta = $this->carpetaActual === null
            ? null
            : $this->carpetaAutorizada($this->carpetaActual);

        $carpetas = DocumentoCarpeta::query()
            ->where('creado_por', $usuarioId)
            ->where('carpeta_padre_id', $this->carpetaActual)
            ->orderBy('nombre')
            ->get();

        $documentos = $carpeta === null
            ? collect()
            : Documento::query()
            ->where('carpeta_id', $carpeta->id)
            ->where('subido_por', $usuarioId)
            ->orderBy('nombre_original')
            ->get();

        return compact(
            'carpeta',
            'carpetas',
            'documentos'
        );
    }
}

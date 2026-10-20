<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Storage;

class ManageDocumentos extends ManageRecords
{
    protected static string $resource = DocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Subir documento')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Subir nuevo documento')
                ->modalSubmitActionLabel('Guardar documento')
                ->modalCancelActionLabel('Volver')
                ->color(Color::hex('#176B45'))

                ->mutateDataUsing(function (array $data): array {

                    $ruta = $data['ruta_archivo'] ?? null;

                    if (! is_string($ruta) || $ruta === '') {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'ruta_archivo' => 'Debe seleccionar un archivo válido.',
                        ]);
                    }

                    $disco = 'documentos_privados';

                    if (! Storage::disk($disco)->exists($ruta)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'ruta_archivo' => 'No se encontró el archivo almacenado.',
                        ]);
                    }

                    $data['nombre_almacenado'] = basename($ruta);

                    $data['nombre_original'] =
                        $data['nombre_original'] ?? basename($ruta);

                    $data['disco'] = $disco;

                    $data['extension'] = strtolower(
                        pathinfo($ruta, PATHINFO_EXTENSION)
                    );

                    $data['tipo_mime'] =
                        Storage::disk($disco)->mimeType($ruta);

                    $data['tamano'] =
                        Storage::disk($disco)->size($ruta);

                    $data['subido_por'] = auth()->id();

                    return $data;
                }),
        ];
    }
}
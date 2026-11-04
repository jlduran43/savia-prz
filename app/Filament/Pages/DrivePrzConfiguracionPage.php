<?php

namespace App\Filament\Pages;

use App\Models\DrivePrzConfiguracion;
use App\Support\DrivePrzAjustes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Textarea;

class DrivePrzConfiguracionPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configuración Drive PRZ';
    protected static ?string $title = 'Configuración Drive PRZ';
    protected static string|\UnitEnum|null $navigationGroup = 'Drive PRZ';
    protected string $view = 'filament.pages.drive-prz-configuracion-page';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->check()
            && auth()->user()?->rol === 'ADMIN';
    }

    public function mount(): void
    {
        $config = DrivePrzConfiguracion::query()->first();

        if (!$config) {
            $config = DrivePrzConfiguracion::query()->create([
                'marca_agua_texto' => 'DRIVE PRZ - SOLO LECTURA',
                'marca_agua_color' => '#14263D',
                'marca_agua_opacidad' => 22,
                'marca_agua_angulo' => -25,
                'usar_tamano_manual' => false,
                'marca_agua_tamano' => null,
            ]);
        }

        $this->form->fill([
            'marca_agua_texto' => $config->marca_agua_texto,
            'marca_agua_color' => $config->marca_agua_color,
            'marca_agua_opacidad' => $config->marca_agua_opacidad,
            'marca_agua_angulo' => $config->marca_agua_angulo,
            'usar_tamano_manual' => $config->usar_tamano_manual,
            'marca_agua_tamano' => $config->marca_agua_tamano,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Marca de agua')
                    ->schema([
                        Forms\Components\TextInput::make('marca_agua_texto')
                            ->label('Texto')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\ColorPicker::make('marca_agua_color')
                            ->label('Color')
                            ->required(),

                        Forms\Components\TextInput::make('marca_agua_opacidad')
                            ->label('Opacidad (%)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->required(),

                        Forms\Components\TextInput::make('marca_agua_angulo')
                            ->label('Ángulo')
                            ->numeric()
                            ->required(),

                        Forms\Components\Toggle::make('usar_tamano_manual')
                            ->label('Usar tamaño manual'),

                        Forms\Components\TextInput::make('marca_agua_tamano')
                            ->label('Tamaño manual')
                            ->numeric()
                            ->minValue(10)
                            ->maxValue(120)
                            ->visible(fn(callable $get) => (bool) $get('usar_tamano_manual')),

                        Textarea::make('watermark_secondary_text')
                            ->label('Texto secundario de la marca de agua')
                            ->placeholder(
                                "Documento de uso interno\nProhibida su distribución"
                            )
                            ->helperText(
                                'Este texto aparecerá debajo de la marca de agua principal. ' .
                                    'Puedes escribir varias líneas. Déjalo vacío para desactivarlo.'
                            )
                            ->rows(3)
                            ->maxLength(500)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function guardar(): void
    {
        $estado = $this->form->getState();

        $config = DrivePrzConfiguracion::query()->first();

        if (!$config) {
            $config = new DrivePrzConfiguracion();
        }

        $config->fill($estado);
        $config->save();

        DrivePrzAjustes::limpiarCache();

        Notification::make()
            ->title('Configuración guardada')
            ->success()
            ->send();
    }
}

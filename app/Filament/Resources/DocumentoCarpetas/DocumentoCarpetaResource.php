<?php

namespace App\Filament\Resources\DocumentoCarpetas;

use App\Filament\Resources\DocumentoCarpetas\Pages\ManageDocumentoCarpetas;
use App\Filament\Resources\DocumentoCarpetas\Schemas\DocumentoCarpetaForm;
use App\Filament\Resources\DocumentoCarpetas\Tables\DocumentoCarpetasTable;
use App\Models\DocumentoCarpeta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentoCarpetaResource extends Resource
{
    protected static ?string $model = DocumentoCarpeta::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $modelLabel = 'carpeta';

    protected static ?string $pluralModelLabel = 'carpetas';

    protected static ?string $navigationLabel = 'Documentos';

    public static function form(Schema $schema): Schema
    {
        return DocumentoCarpetaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentoCarpetasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDocumentoCarpetas::route('/'),
        ];
    }
}

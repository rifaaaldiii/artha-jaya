<?php

namespace App\Filament\Resources\JasaDanLayanans;

use App\Filament\Resources\JasaDanLayanans\Pages\CreateJasaDanLayanan;
use App\Filament\Resources\JasaDanLayanans\Pages\EditJasaDanLayanan;
use App\Filament\Resources\JasaDanLayanans\Pages\ListJasaDanLayanans;
use App\Filament\Resources\JasaDanLayanans\Schemas\JasaDanLayananForm;
use App\Filament\Resources\JasaDanLayanans\Tables\JasaDanLayanansTable;
use App\Models\JasaDanLayanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JasaDanLayananResource extends Resource
{
    protected static ?string $model = JasaDanLayanan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function form(Schema $schema): Schema
    {
        return JasaDanLayananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JasaDanLayanansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJasaDanLayanans::route('/'),
            'create' => CreateJasaDanLayanan::route('/create'),
            'edit' => EditJasaDanLayanan::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }
}

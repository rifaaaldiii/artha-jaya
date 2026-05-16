<?php

namespace App\Filament\Resources\KategoriJasaItems;

use App\Filament\Resources\KategoriJasaItems\Pages;
use App\Filament\Resources\KategoriJasaItems\Schemas\KategoriJasaItemForm;
use App\Filament\Resources\KategoriJasaItems\Tables\KategoriJasaItemsTable;
use App\Models\KategoriJasaItem;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class KategoriJasaItemResource extends Resource
{
    protected static ?string $model = KategoriJasaItem::class;

    protected static ?string $navigationLabel = 'Kategori Jasa';

    protected static UnitEnum|string|null $navigationGroup = 'Jasa & Layanan';

    protected static ?int $navigationSort = 4;

    public static function getLabel(): ?string
    {
        return 'Kategori';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Kategori Jasa';
    }

    public static function form(Schema $schema): Schema
    {
        return KategoriJasaItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriJasaItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKategoriJasaItems::route('/'),
        ];
    }

    protected static function canManage(): bool
    {
        $user = Auth::user();
    
        return $user && in_array($user->role, ['administrator', 'superadmin'], true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return static::canManage();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canManage();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canManage();
    }

    public static function canDeleteAny(): bool
    {
        return static::canManage();
    }
}

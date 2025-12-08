<?php

namespace App\Filament\Resources\JenisProduksis;

use App\Filament\Resources\JenisProduksis\Pages;
use App\Filament\Resources\JenisProduksis\Schemas\JenisProduksiForm;
use App\Filament\Resources\JenisProduksis\Tables\JenisProduksisTable;
use App\Models\JenisProduksi;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JenisProduksiResource extends Resource
{
    protected static ?string $model = JenisProduksi::class;

    protected static ?string $navigationLabel = 'Jenis Produksi';

    protected static UnitEnum|string|null $navigationGroup = 'Product';

    protected static ?int $navigationSort = 3;

    public static function getLabel(): ?string
    {
        return 'Jenis';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Jenis Produksi';
    }

    public static function form(Schema $schema): Schema
    {
        return JenisProduksiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisProduksisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJenisProduksis::route('/'),
        ];
    }

    protected static function canManage(): bool
    {
        $user = Auth::user();

        return $user && in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canManage();
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


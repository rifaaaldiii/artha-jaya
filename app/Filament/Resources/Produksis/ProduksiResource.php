<?php

namespace App\Filament\Resources\Produksis;

use App\Filament\Resources\Produksis\Pages\CreateProduksi;
use App\Filament\Resources\Produksis\Pages\EditProduksi;
use App\Filament\Resources\Produksis\Pages\ListProduksis;
use App\Filament\Resources\Produksis\Schemas\ProduksiForm;
use App\Filament\Resources\Produksis\Tables\ProduksisTable;
use App\Models\produksi as Produksi;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;
    protected static ?string $title = 'Product Input';
    public static function getNavigationLabel(): string
    {
        return 'Product Input';
    }
    public static function form(Schema $schema): Schema
    

    {
        return ProduksiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduksisTable::configure($table);
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
            'index' => ListProduksis::route('/'),
            'create' => CreateProduksi::route('/create'),
            'edit' => EditProduksi::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Product';
    }

    public static function canCreate(): bool
    {
        return static::userHasAdminPrivileges();
    }

    public static function canEdit(Model $record): bool
    {
        return static::userHasAdminPrivileges();
    }

    public static function canDelete(Model $record): bool
    {
        return static::userHasAdminPrivileges();
    }

    public static function canDeleteAny(): bool
    {
        return static::userHasAdminPrivileges();
    }

    public static function userHasAdminPrivileges(): bool
    {
        $role = Auth::user()?->role;

        if (! $role) {
            return false;
        }

        $normalizedRole = str_replace(' ', '_', strtolower($role));

        return in_array($normalizedRole, ['administrator', 'admin_toko'], true);
    }
}

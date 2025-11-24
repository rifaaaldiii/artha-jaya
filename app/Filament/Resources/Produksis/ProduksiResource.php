<?php

namespace App\Filament\Resources\Produksis;

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
    public static function getLabel(): ?string
    {
        return 'Produksi';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Produksi';
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
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Product';
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko', 'kepala_teknisi_gudang', 'petukang', 'admin_gudang'], true);
    }
}

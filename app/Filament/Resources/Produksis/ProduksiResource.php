<?php

namespace App\Filament\Resources\Produksis;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\Produksi as Produksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Produksis\Pages\ListProduksis;
use App\Filament\Resources\Produksis\Schemas\ProduksiForm;
use App\Filament\Resources\Produksis\Tables\ProduksisTable;

class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;
    protected static ?string $title = 'Product Input';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'no_produksi',
            'nama_produksi',
            'nama_bahan',
            'status',
            'catatan',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->no_produksi 
            . ' | ' 
            . $record->nama_produksi 
            . (!empty($record->nama_bahan) ? ' [Bahan: ' . $record->nama_bahan . ']' : '');
    }

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

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('progress')
                ->label('Progress Product')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->url(route('filament.admin.pages.progress', [
                    'produksi' => $record->getKey(),
                ]))
        ];
    }
}

<?php

namespace App\Filament\Resources\Produksis;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\Produksi as Produksi;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\Produksis\Pages\EditProduksi;
use App\Filament\Resources\Produksis\Pages\ListProduksis;
use App\Filament\Resources\Produksis\Pages\CreateProduksi;
use App\Filament\Resources\Produksis\Schemas\ProduksiForm;
use App\Filament\Resources\Produksis\Tables\ProduksisTable;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;
    protected static ?string $title = 'Product Input';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'no_produksi',
            'no_ref',
            'status',
            'catatan',
            'items.nama_produksi',
            'items.nama_bahan',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $itemsInfo = '';
        if ($record->items && $record->items->count() > 0) {
            $firstItem = $record->items->first();
            $itemsInfo = ' | ' . $firstItem->nama_produksi;
            if ($record->items->count() > 1) {
                $itemsInfo .= ' (+' . ($record->items->count() - 1) . ' items)';
            }
        }
        
        return $record->no_produksi . $itemsInfo;
    }

    public static function getNavigationLabel(): string
    {
        return 'Input';
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withCount('items');
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
        return 'Jasa StepNosing / Plint';
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['no_produksi'])) {
            $prefix = 'P-';
            $padLength = 5;

            $lastNo = Produksi::query()
                ->where('no_produksi', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->value('no_produksi');

            $nextNum = $lastNo
                ? (int) substr($lastNo, strlen($prefix)) + 1
                : 1;

            $data['no_produksi'] = $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
        }

        if (empty($data['status'])) {
            $data['status'] = 'baru';
        }

        if (!empty($data['create_new_pelanggan'])) {
            $existingPelanggan = Pelanggan::where('nama', $data['new_pelanggan_nama'] ?? null)
                ->where('kontak', $data['new_pelanggan_kontak'] ?? null)
                ->where('alamat', $data['alamat'] ?? null)
                ->first();

            if ($existingPelanggan) {
                throw ValidationException::withMessages([
                    'new_pelanggan_nama' => ['Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.'],
                ]);
            }

            $pelanggan = Pelanggan::create([
                'nama' => $data['new_pelanggan_nama'],
                'kontak' => $data['new_pelanggan_kontak'],
                'alamat' => $data['alamat'],
                'createdAt' => now(),
            ]);

            $data['pelanggan_id'] = $pelanggan->id;
        }

        unset(
            $data['create_new_pelanggan'],
            $data['new_pelanggan_nama'],
            $data['new_pelanggan_kontak'],
        );

        return $data;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
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

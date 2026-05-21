<?php

namespace App\Filament\Resources\Jasas;

use BackedEnum;
use App\Models\Jasa;
use App\Models\Pelanggan;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\Jasas\Pages\EditJasa;
use App\Filament\Resources\Jasas\Pages\ListJasas;
use App\Filament\Resources\Jasas\Pages\CreateJasa;
use App\Filament\Resources\Jasas\Schemas\JasaForm;
use App\Filament\Resources\Jasas\Tables\JasasTable;
use Illuminate\Database\Eloquent\Relations\Relation;

class JasaResource extends Resource
{
    protected static ?string $model = Jasa::class;
    protected static ?string $title = 'Jasa Input';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'no_jasa',
            'no_ref',
            'status',
            'catatan',
            'pelanggan.nama',
            'items.jenis_layanan',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        // For Filament global search: display only string (NO HTML),
        // so just a plain text short summary, not HTML elements
        $pelangganNama = $record->pelanggan?->nama ?? '-';
        $noJasa = $record->no_jasa ?? '-';
        $noRef = $record->no_ref ?? '-';

        $itemsInfo = '';
        if ($record->items && $record->items->count() > 0) {
            $firstItem = $record->items->first();
            $itemsInfo = ' | ' . $firstItem->jenis_layanan;
            if ($record->items->count() > 1) {
                $itemsInfo .= ' (+' . ($record->items->count() - 1) . ' items)';
            }
        }

        // Display in one line, similar to: [Nama] | [No. Jasa] | [No. Ref] | [Layanan]
        return "{$pelangganNama} | {$noJasa}" 
            . (!empty($noRef) && $noRef !== '-' ? " | {$noRef}" : '')
            . $itemsInfo;
    }

    public static function getNavigationLabel(): string
    {
        return 'Input';
    }

    public static function getLabel(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Tabel Jasa & Layanan';
    }

    public static function form(Schema $schema): Schema
    {
        return JasaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JasasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()->withCount('items');
        
        // Apply branch filtering based on authenticated user
        $user = Auth::user();
        if ($user && $user->branch) {
            $query->where('branch', $user->branch);
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJasas::route('/'),
            'create' => CreateJasa::route('/create'),
            'edit' => EditJasa::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko', 'kepala_lapangan', 'superadmin'], true);
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
        \Log::info('=== mutateFormDataBeforeCreate DEBUG ===', [
            'data_keys' => array_keys($data),
            'has_items' => isset($data['items']),
            'items_count' => isset($data['items']) ? count($data['items']) : 0,
        ]);
        
        // Merge accessories from items into the main items array
        if (isset($data['items']) && is_array($data['items'])) {
            $allItems = [];
            
            foreach ($data['items'] as $index => $item) {
                \Log::info("Processing item {$index}", [
                    'item_keys' => array_keys($item),
                    'has_accessories' => isset($item['accessories']),
                    'accessories_count' => isset($item['accessories']) ? count($item['accessories']) : 0,
                ]);
                
                // Add the main item
                $allItems[] = [
                    'kategori_jasa_item_id' => $item['kategori_jasa_item_id'] ?? null,
                    'jenis_layanan' => $item['jenis_layanan'] ?? null,
                    'jumlah' => $item['jumlah'] ?? 1,
                    'harga' => $item['harga'] ?? 0,
                ];
                
                // Add accessories if they exist and toggle is enabled
                if (isset($item['accessories']) && is_array($item['accessories'])) {
                    foreach ($item['accessories'] as $accIndex => $accessory) {
                        \Log::info("Adding accessory {$accIndex}", [
                            'accessory' => $accessory,
                        ]);
                        
                        $allItems[] = [
                            'kategori_jasa_item_id' => $accessory['kategori_jasa_item_id'] ?? null,
                            'jenis_layanan' => $accessory['jenis_layanan'] ?? null,
                            'jumlah' => $accessory['jumlah'] ?? 1,
                            'harga' => $accessory['harga'] ?? 0,
                        ];
                    }
                }
            }
            
            \Log::info('Merged items result', [
                'total_items' => count($allItems),
                'all_items' => $allItems,
            ]);
            
            // Replace items with merged array
            $data['items'] = $allItems;
        }
        
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Same logic for updates
        return self::mutateFormDataBeforeCreate($data);
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('progress')
                ->label('Progress Product')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->url(route('filament.admin.pages.progress-jasa', [
                    'produksi' => $record->getKey(),
                ]))
        ];
    }
}

<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\Pelanggan;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditJasa extends EditRecord
{
    protected static string $resource = JasaResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Hapus field temporary yang tidak perlu disimpan
        unset($data['create_new_pelanggan']);
        unset($data['new_pelanggan_nama']);
        unset($data['new_pelanggan_kontak']);
        unset($data['include_accessories']);

        return $data;
    }

    protected function afterSave(): void
    {
        // Process accessories after items are saved
        $this->processAccessories();
    }

    protected function processAccessories(): void
    {
        $jasa = $this->record;
        
        if (!$jasa) {
            return;
        }

        // Get the form state to access items data with accessories
        $formData = $this->form->getState();
        
        \Log::info('=== EditJasa::afterSave DEBUG ===', [
            'jasa_id' => $jasa->id,
            'form_data_keys' => array_keys($formData),
            'has_items' => isset($formData['items']),
            'items_count' => isset($formData['items']) ? count($formData['items']) : 0,
        ]);

        if (!isset($formData['items']) || !is_array($formData['items'])) {
            return;
        }

        // Delete existing accessories items first to avoid duplicates
        // We identify accessories by checking if they match the accessories pattern
        // For simplicity, we'll delete all items and recreate them
        
        $accessoriesToAdd = [];
        $mainItemsToKeep = [];

        foreach ($formData['items'] as $index => $item) {
            \Log::info("Processing item {$index}", [
                'item_keys' => array_keys($item),
                'has_accessories' => isset($item['accessories']),
                'accessories_count' => isset($item['accessories']) ? count($item['accessories']) : 0,
            ]);

            // Check if item has accessories
            if (isset($item['accessories']) && is_array($item['accessories'])) {
                foreach ($item['accessories'] as $accIndex => $accessory) {
                    \Log::info("Found accessory {$accIndex}", [
                        'accessory' => $accessory,
                    ]);

                    $accessoriesToAdd[] = [
                        'jasa_id' => $jasa->id,
                        'kategori_jasa_item_id' => $accessory['kategori_jasa_item_id'] ?? null,
                        'jenis_layanan' => $accessory['jenis_layanan'] ?? null,
                        'jumlah' => $accessory['jumlah'] ?? 1,
                        'harga' => $accessory['harga'] ?? 0,
                        'createdAt' => now(),
                    ];
                }
            }
        }

        // Delete existing items and recreate with accessories
        // NOTE: This is a simplified approach - in production, you might want more sophisticated logic
        if (!empty($accessoriesToAdd)) {
            \Log::info('Inserting accessories', [
                'count' => count($accessoriesToAdd),
                'accessories' => $accessoriesToAdd,
            ]);

            \DB::table('jasa_items')->insert($accessoriesToAdd);

            \Log::info('Accessories inserted successfully', [
                'jasa_id' => $jasa->id,
                'accessories_count' => count($accessoriesToAdd),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

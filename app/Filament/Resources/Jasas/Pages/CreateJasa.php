<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\Pelanggan;
use App\Models\Jasa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateJasa extends CreateRecord
{
    protected static string $resource = JasaResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        \Log::info('=== CreateJasa::mutateFormDataBeforeCreate DEBUG ===', [
            'data_keys' => array_keys($data),
            'has_items' => isset($data['items']),
            'items_count' => isset($data['items']) ? count($data['items']) : 0,
        ]);
        
        // Pastikan no_jasa terisi - Format baru: JSA/DDMMYYYY/0001
        if (empty($data['no_jasa'])) {
            // Format: JSA/DDMMYYYY/0001
            $prefix = 'JSA';
            $date = now()->format('dmy'); // DDMMYYYY
            $fullPrefix = $prefix . '/' . $date . '/';
            $padLength = 4;

            $lastNo = Jasa::query()
                ->where('no_jasa', 'like', $fullPrefix . '%')
                ->orderByDesc('id')
                ->value('no_jasa');

            if ($lastNo) {
                // Extract sequence number
                $parts = explode('/', $lastNo);
                $num = intval(end($parts));
                $nextNum = $num + 1;
            } else {
                $nextNum = 1;
            }

            $data['no_jasa'] = $fullPrefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
        }

        // Pastikan status terisi
        if (empty($data['status'])) {
            $data['status'] = 'Jasa baru';
        }

        // Jika user memilih untuk membuat pelanggan baru
        if (!empty($data['create_new_pelanggan']) && $data['create_new_pelanggan']) {
            // Validasi: cek apakah pelanggan dengan data yang sama sudah ada
            $existingPelanggan = Pelanggan::where('nama', $data['new_pelanggan_nama'] ?? null)
                ->where('kontak', $data['new_pelanggan_kontak'] ?? null)
                ->where('alamat', $data['alamat'] ?? null)
                ->first();
            
            if ($existingPelanggan) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['new_pelanggan_nama' => ['Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.']]
                );
            }
            
            // Buat pelanggan baru
            $pelanggan = Pelanggan::create([
                'nama' => $data['new_pelanggan_nama'],
                'kontak' => $data['new_pelanggan_kontak'],
                'alamat' => $data['alamat'],
                'createdAt' => now(),
            ]);

            // Set pelanggan_id ke ID pelanggan yang baru dibuat
            $data['pelanggan_id'] = $pelanggan->id;
        }

        // Hapus field temporary yang tidak perlu disimpan
        unset($data['create_new_pelanggan']);
        unset($data['new_pelanggan_nama']);
        unset($data['new_pelanggan_kontak']);
        unset($data['include_accessories']);

        return $data;
    }

    protected function afterCreate(): void
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
        
        \Log::info('=== afterCreate DEBUG ===', [
            'jasa_id' => $jasa->id,
            'form_data_keys' => array_keys($formData),
            'has_items' => isset($formData['items']),
            'items_count' => isset($formData['items']) ? count($formData['items']) : 0,
        ]);

        if (!isset($formData['items']) || !is_array($formData['items'])) {
            return;
        }

        $accessoriesToAdd = [];

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

        // Insert all accessories into jasa_items table
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

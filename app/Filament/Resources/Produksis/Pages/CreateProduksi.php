<?php

namespace App\Filament\Resources\Produksis\Pages;

use App\Filament\Resources\Produksis\ProduksiResource;
use App\Models\Pelanggan;
use App\Models\Produksi;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduksi extends CreateRecord
{
    protected static string $resource = ProduksiResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan no_produksi terisi
        if (empty($data['no_produksi'])) {
            $prefix = 'P-';
            $padLength = 5;
            $lastNo = Produksi::query()
                ->where('no_produksi', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->value('no_produksi');

            if ($lastNo) {
                $num = intval(substr($lastNo, strlen($prefix)));
                $nextNum = $num + 1;
            } else {
                $nextNum = 1;
            }

            $data['no_produksi'] = $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
        }

        // Pastikan status terisi
        if (empty($data['status'])) {
            $data['status'] = 'baru';
        }

        // Jika user memilih untuk membuat pelanggan baru
        if (!empty($data['create_new_pelanggan']) && $data['create_new_pelanggan']) {
            // Validasi: cek apakah pelanggan dengan data yang sama sudah ada
            $existingPelanggan = Pelanggan::where('nama', $data['new_pelanggan_nama'])
                ->where('kontak', $data['new_pelanggan_kontak'])
                ->where('alamat', $data['alamat'])
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

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
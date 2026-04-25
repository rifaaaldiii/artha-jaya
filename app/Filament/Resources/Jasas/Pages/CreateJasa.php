<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\Pelanggan;
use App\Models\Jasa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

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

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

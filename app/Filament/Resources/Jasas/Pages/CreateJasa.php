<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\pelanggan;
use App\Models\jasa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJasa extends CreateRecord
{
    protected static string $resource = JasaResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan no_jasa terisi
        if (empty($data['no_jasa'])) {
            $prefix = 'J-';
            $padLength = 5;
            $lastNo = jasa::query()
                ->where('no_jasa', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->value('no_jasa');

            if ($lastNo) {
                $num = intval(substr($lastNo, strlen($prefix)));
                $nextNum = $num + 1;
            } else {
                $nextNum = 1;
            }

            $data['no_jasa'] = $prefix . str_pad($nextNum, $padLength, '0', STR_PAD_LEFT);
        }

        // Pastikan status terisi
        if (empty($data['status'])) {
            $data['status'] = 'Jasa baru';
        }

        // Jika user memilih untuk membuat pelanggan baru
        if (!empty($data['create_new_pelanggan']) && $data['create_new_pelanggan']) {
            // Validasi: cek apakah pelanggan dengan data yang sama sudah ada
            $existingPelanggan = pelanggan::where('nama', $data['new_pelanggan_nama'])
                ->where('kontak', $data['new_pelanggan_kontak'])
                ->where('alamat', $data['new_pelanggan_alamat'])
                ->first();
            
            if ($existingPelanggan) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['new_pelanggan_nama' => ['Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.']]
                );
            }
            
            // Buat pelanggan baru
            $pelanggan = pelanggan::create([
                'nama' => $data['new_pelanggan_nama'],
                'kontak' => $data['new_pelanggan_kontak'],
                'alamat' => $data['new_pelanggan_alamat'],
                'createdAt' => now(),
            ]);

            // Set pelanggan_id ke ID pelanggan yang baru dibuat
            $data['pelanggan_id'] = $pelanggan->id;
        }

        // Hapus field temporary yang tidak perlu disimpan
        unset($data['create_new_pelanggan']);
        unset($data['new_pelanggan_nama']);
        unset($data['new_pelanggan_kontak']);
        unset($data['new_pelanggan_alamat']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

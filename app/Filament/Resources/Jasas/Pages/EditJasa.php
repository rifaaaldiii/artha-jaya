<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\pelanggan;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditJasa extends EditRecord
{
    protected static string $resource = JasaResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko'], true);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan perubahan pelanggan jika ada
        if (!empty($data['pelanggan_id']) && isset($data['edit_pelanggan_nama'])) {
            $pelanggan = pelanggan::find($data['pelanggan_id']);
            if ($pelanggan) {
                // Validasi: cek apakah ada pelanggan lain dengan data yang sama
                $existingPelanggan = pelanggan::where('nama', $data['edit_pelanggan_nama'])
                    ->where('kontak', $data['edit_pelanggan_kontak'])
                    ->where('alamat', $data['edit_pelanggan_alamat'])
                    ->where('id', '!=', $data['pelanggan_id'])
                    ->first();
                
                if ($existingPelanggan) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['edit_pelanggan_nama' => ['Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.']]
                    );
                }
                
                $pelanggan->update([
                    'nama' => $data['edit_pelanggan_nama'] ?? $pelanggan->nama,
                    'kontak' => $data['edit_pelanggan_kontak'] ?? $pelanggan->kontak,
                    'alamat' => $data['edit_pelanggan_alamat'] ?? $pelanggan->alamat,
                ]);
            }
        }

        // Hapus field temporary yang tidak perlu disimpan
        unset($data['create_new_pelanggan']);
        unset($data['new_pelanggan_nama']);
        unset($data['new_pelanggan_kontak']);
        unset($data['new_pelanggan_alamat']);
        unset($data['edit_pelanggan_nama']);
        unset($data['edit_pelanggan_kontak']);
        unset($data['edit_pelanggan_alamat']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

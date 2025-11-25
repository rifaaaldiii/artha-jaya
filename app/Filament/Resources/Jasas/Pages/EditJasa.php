<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use App\Models\Pelanggan;
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
        if (!empty($data['pelanggan_id'])) {
            $pelanggan = Pelanggan::find($data['pelanggan_id']);
            if ($pelanggan) {
                // Cek apakah ada perubahan pada data pelanggan
                $hasChanges = false;
                $newNama = $data['edit_pelanggan_nama'] ?? null;
                $newKontak = $data['edit_pelanggan_kontak'] ?? null;
                $newAlamat = $data['edit_pelanggan_alamat'] ?? null;
                
                if ($newNama && $newNama !== $pelanggan->nama) {
                    $hasChanges = true;
                }
                if ($newKontak && $newKontak !== $pelanggan->kontak) {
                    $hasChanges = true;
                }
                if ($newAlamat && $newAlamat !== $pelanggan->alamat) {
                    $hasChanges = true;
                }
                
                if ($hasChanges && ($newNama || $newKontak || $newAlamat)) {
                    // Validasi: cek apakah ada pelanggan lain dengan data yang sama
                    $existingPelanggan = Pelanggan::where('nama', $newNama ?? $pelanggan->nama)
                        ->where('kontak', $newKontak ?? $pelanggan->kontak)
                        ->where('alamat', $newAlamat ?? $pelanggan->alamat)
                        ->where('id', '!=', $data['pelanggan_id'])
                        ->first();
                    
                    if ($existingPelanggan) {
                        throw new \Illuminate\Validation\ValidationException(
                            validator([], []),
                            ['edit_pelanggan_nama' => ['Pelanggan dengan nama, kontak, dan alamat yang sama sudah ada.']]
                        );
                    }
                    
                    // Update data pelanggan
                    $pelanggan->update([
                        'nama' => $newNama ?? $pelanggan->nama,
                        'kontak' => $newKontak ?? $pelanggan->kontak,
                        'alamat' => $newAlamat ?? $pelanggan->alamat,
                    ]);
                }
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

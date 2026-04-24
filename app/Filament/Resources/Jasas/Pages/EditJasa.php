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

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

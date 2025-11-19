<?php

namespace App\Filament\Resources\Petukangs\Pages;

use App\Filament\Resources\Petukangs\PetukangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPetukang extends EditRecord
{
    protected static string $resource = PetukangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\Petukangs\Pages;

use App\Filament\Resources\Petukangs\PetukangResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePetukang extends CreateRecord
{
    protected static string $resource = PetukangResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

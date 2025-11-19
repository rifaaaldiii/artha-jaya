<?php

namespace App\Filament\Resources\Petugas\Pages;

use App\Filament\Resources\Petugas\PetugasResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePetugas extends CreateRecord
{
    protected static string $resource = PetugasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

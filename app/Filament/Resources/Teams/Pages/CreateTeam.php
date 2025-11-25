<?php

namespace App\Filament\Resources\Teams\Pages;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Teams\TeamResource;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

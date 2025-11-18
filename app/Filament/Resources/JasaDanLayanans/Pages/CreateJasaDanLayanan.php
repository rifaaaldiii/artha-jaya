<?php

namespace App\Filament\Resources\JasaDanLayanans\Pages;

use App\Filament\Resources\JasaDanLayanans\JasaDanLayananResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJasaDanLayanan extends CreateRecord
{
    protected static string $resource = JasaDanLayananResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

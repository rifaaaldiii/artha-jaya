<?php

namespace App\Filament\Resources\JasaDanLayanans\Pages;

use App\Filament\Resources\JasaDanLayanans\JasaDanLayananResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJasaDanLayanan extends EditRecord
{
    protected static string $resource = JasaDanLayananResource::class;

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

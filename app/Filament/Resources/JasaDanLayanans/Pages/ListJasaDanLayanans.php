<?php

namespace App\Filament\Resources\JasaDanLayanans\Pages;

use App\Filament\Resources\JasaDanLayanans\JasaDanLayananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJasaDanLayanans extends ListRecords
{
    protected static string $resource = JasaDanLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

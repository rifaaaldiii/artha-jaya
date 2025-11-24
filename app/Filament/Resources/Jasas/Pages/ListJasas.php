<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ListJasas extends ManageRecords
{
    protected static string $resource = JasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize(JasaResource::canCreate())
                ->mutateFormDataUsing(fn (array $data): array => JasaResource::mutateFormDataBeforeCreate($data)),
        ];
    }
}

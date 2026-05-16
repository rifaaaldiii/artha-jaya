<?php

namespace App\Filament\Resources\KategoriJasaItems\Pages;

use App\Filament\Resources\KategoriJasaItems\KategoriJasaItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoriJasaItems extends ManageRecords
{
    protected static string $resource = KategoriJasaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize(KategoriJasaItemResource::canCreate()),
        ];
    }
}

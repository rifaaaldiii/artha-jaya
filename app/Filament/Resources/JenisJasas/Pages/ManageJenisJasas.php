<?php

namespace App\Filament\Resources\JenisJasas\Pages;

use App\Filament\Resources\JenisJasas\JenisJasaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisJasas extends ManageRecords
{
    protected static string $resource = JenisJasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize(JenisJasaResource::canCreate()),
        ];
    }
}


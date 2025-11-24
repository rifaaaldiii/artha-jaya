<?php

namespace App\Filament\Resources\Petukangs\Pages;

use App\Filament\Resources\Petukangs\PetukangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ListPetukangs extends ManageRecords
{
    protected static string $resource = PetukangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Accessoris\Pages;

use App\Filament\Resources\Accessoris\AccessoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAccessoris extends ManageRecords
{
    protected static string $resource = AccessoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize(AccessoriResource::canCreate()),
        ];
    }
}

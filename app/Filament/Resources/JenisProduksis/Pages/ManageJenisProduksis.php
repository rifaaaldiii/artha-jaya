<?php

namespace App\Filament\Resources\JenisProduksis\Pages;

use App\Filament\Resources\JenisProduksis\JenisProduksiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisProduksis extends ManageRecords
{
    protected static string $resource = JenisProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize(JenisProduksiResource::canCreate()),
        ];
    }
}


<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;

class ListJasas extends ManageRecords
{
    protected static string $resource = JasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize(JasaResource::canCreate())
                ->url(JasaResource::getUrl('create')),
        ];
    }

    #[On('aj-refresh-jasa')]
    public function handleExternalRefresh(): void
    {
        $this->resetTable();
    }
}

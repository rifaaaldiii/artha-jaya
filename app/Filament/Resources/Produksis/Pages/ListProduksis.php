<?php

namespace App\Filament\Resources\Produksis\Pages;

use App\Filament\Resources\Produksis\ProduksiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;

class ListProduksis extends ManageRecords
{
    protected static string $resource = ProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => ProduksiResource::canCreate()),
        ];
    }

    #[On('aj-refresh-produksi')]
    public function handleExternalRefresh(): void
    {
        $this->resetTable();
    }
}

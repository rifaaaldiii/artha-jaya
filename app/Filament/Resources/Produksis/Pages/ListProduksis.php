<?php

namespace App\Filament\Resources\Produksis\Pages;

use App\Filament\Resources\Produksis\ProduksiResource;
use App\Filament\Resources\Produksis\Schemas\ProduksiForm;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Schema;
use Livewire\Attributes\On;

class ListProduksis extends ManageRecords
{
    protected static string $resource = ProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => ProduksiResource::canCreate())
                ->form(fn (Schema $schema) => ProduksiForm::configure($schema)),
        ];
    }

    #[On('aj-refresh-produksi')]
    public function handleExternalRefresh(): void
    {
        $this->resetTable();
    }
}

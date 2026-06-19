<?php

namespace App\Filament\Resources\Jasas\Pages;

use App\Filament\Resources\Jasas\JasaResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;

class ListJasas extends ManageRecords
{
    protected static string $resource = JasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelled')
                ->label('Jasa Dibatalkan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->url(JasaResource::getUrl('cancelled')),
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

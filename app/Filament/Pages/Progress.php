<?php

namespace App\Filament\Pages;

use App\Models\produksi;
use Filament\Pages\Page;
use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

class Progress extends Page
{

    protected string $view = 'filament.pages.progress';
    
    protected static ?string $navigationLabel = 'Progress Product';
    
    protected static ?string $title = 'Progress';
    
    protected static ?int $navigationSort = 2;

    public ?int $selectedProduksiId = null;
    
    public ?produksi $record = null;

    public static function getNavigationGroup(): ?string
    {
        return 'Product';
    }


    protected function loadRecord(): void
    {
        if ($this->selectedProduksiId) {
            $this->record = produksi::with('team')->find($this->selectedProduksiId);
        } else {
            $this->record = null;
        }
    }

    // Enable polling for real-time updates (every 3 seconds)
    protected function getPollingInterval(): ?string
    {
        return '3s';
    }

    // Refresh record when polling
    public function refresh(): void
    {
        if ($this->record) {
            $this->record->refresh();
        }
    }

    public function mount(): void
    {
        // Check if selectedProduksiId is provided from query string
        $selectedProduksiId = request()->query('selectedProduksiId');
        
        if ($selectedProduksiId) {
            $this->selectedProduksiId = (int) $selectedProduksiId;
            $this->loadRecord();
        } else {
            // Load the first produksi if none selected
            $firstProduksi = produksi::orderBy('createdAt', 'desc')->first();
            if ($firstProduksi) {
                $this->selectedProduksiId = $firstProduksi->id;
                $this->loadRecord();
            }
        }
    }

    public function updatedSelectedProduksiId(): void
    {
        $this->loadRecord();
    }

    public function getProduksiOptions(): array
    {
        return produksi::query()
            ->orderBy('createdAt', 'desc')
            ->get()
            ->mapWithKeys(function ($produksi) {
                return [
                    $produksi->id => 
                        $produksi->no_produksi . ' | ' .
                        $produksi->nama_produksi . ' - ' .
                        $produksi->nama_bahan
                ];
            })
            ->toArray();
    }
}
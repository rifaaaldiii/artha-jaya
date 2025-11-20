<?php

namespace App\Filament\Pages;

use App\Models\jasa;
use Filament\Pages\Page;

class ProgressJasa extends Page
{
    protected string $view = 'filament.pages.progressJasa';

    protected static ?string $navigationLabel = 'Progress Jasa';

    protected static ?string $title = 'Progress Jasa';

    protected static ?int $navigationSort = 2;

    public ?int $selectedJasaId = null;

    public ?jasa $record = null;

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    protected function loadRecord(): void
    {
        $this->record = $this->selectedJasaId
            ? jasa::with(['petugas', 'pelanggan'])->find($this->selectedJasaId)
            : null;
    }

    protected function getPollingInterval(): ?string
    {
        return '3s';
    }

    public function refresh(): void
    {
        if ($this->record) {
            $this->record->refresh();
        }
    }

    public function mount(): void
    {
        $selectedJasaId = request()->query('selectedJasaId');

        if ($selectedJasaId) {
            $this->selectedJasaId = (int) $selectedJasaId;
            $this->loadRecord();
            return;
        }

        $firstJasa = jasa::orderBy('createdAt', 'desc')->first();
        if ($firstJasa) {
            $this->selectedJasaId = $firstJasa->id;
            $this->loadRecord();
        }
    }

    public function updatedSelectedJasaId(): void
    {
        $this->loadRecord();
    }

    public function getJasaOptions(): array
    {
        return jasa::query()
            ->with('pelanggan')
            ->orderBy('createdAt', 'desc')
            ->get()
            ->mapWithKeys(function ($jasa) {
                $pelanggan = $jasa->pelanggan?->nama ? ' - ' . $jasa->pelanggan->nama : '';

                return [
                    $jasa->id => $jasa->no_ref . ' | ' . $jasa->jenis_layanan . $pelanggan,
                ];
            })
            ->toArray();
    }
}
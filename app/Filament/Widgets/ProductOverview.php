<?php

namespace App\Filament\Widgets;

use App\Models\Jasa;
use App\Models\Pelanggan;
use App\Models\Produksi;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class ProductOverview extends Widget
{
    protected string $view = 'filament.widgets.product-overview';

    protected int | string | array $columnSpan = 'full';

    public function getStatsProperty(): array
    {
        $totalProduksi = Produksi::count();
        $activeProduksi = Produksi::where('status', '!=', 'selesai')->count();
        $completedProduksi = Produksi::where('status', 'selesai')->count();

        $totalJasa = Jasa::count();
        $scheduledJasa = Jasa::where('status', '!=', 'selesai')->count();
        $completedJasa = Jasa::where('status', 'selesai')->count();

        $totalPelanggan = Pelanggan::count();
        $activePelanggan = Pelanggan::query()
            ->where(function ($query): void {
                $query->whereHas('jasas', fn ($q) => $q->where('status', '!=', 'selesai'))
                    ->orWhereHas('produksis', fn ($q) => $q->where('status', '!=', 'selesai'));
            })
            ->count();

        return [
            [
                'label' => 'Produksi',
                'value' => number_format($totalProduksi, 0, ',', '.'),
                'trend' => $this->calculateTrend(Produksi::class, 'createdAt'),
            ],
            [
                'label' => 'Jasa & Layanan',
                'value' => number_format($totalJasa, 0, ',', '.'),
                'trend' => $this->calculateTrend(Jasa::class, 'createdAt'),
            ],
            [
                'label' => 'Customers',
                'value' => number_format($totalPelanggan, 0, ',', '.'),
                'trend' => $this->calculateTrend(Pelanggan::class, 'createdAt'),
            ],
            [
                'label' => 'Customer Aktif',
                'value' => number_format($activePelanggan, 0, ',', '.'),
                'trend' => $this->calculateActiveCustomerTrend(),
            ],
        ];
    }

    #[On('aj-refresh-dashboard')]
    public function handleExternalRefresh(): void
    {
        $this->dispatch('$refresh');
    }

    /**
     * @param class-string $model
     */
    protected function calculateTrend(string $model, string $column): array
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $current = $model::query()
            ->where($column, '>=', $currentMonthStart)
            ->count();

        $previous = $model::query()
            ->whereBetween($column, [$previousMonthStart, $previousMonthEnd])
            ->count();

        return $this->formatTrend($current, $previous);
    }

    protected function calculateActiveCustomerTrend(): array
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $activeQuery = fn () => Pelanggan::query()
            ->where(function ($query): void {
                $query->whereHas('jasas', fn ($q) => $q->where('status', '!=', 'selesai'))
                    ->orWhereHas('produksis', fn ($q) => $q->where('status', '!=', 'selesai'));
            });

        $current = $activeQuery()
            ->where(function ($query) use ($currentMonthStart): void {
                $query->whereHas('jasas', fn ($q) => $q->where('createdAt', '>=', $currentMonthStart))
                    ->orWhereHas('produksis', fn ($q) => $q->where('createdAt', '>=', $currentMonthStart));
            })
            ->count();

        $previous = $activeQuery()
            ->where(function ($query) use ($previousMonthStart, $previousMonthEnd): void {
                $query->whereHas('jasas', fn ($q) => $q->whereBetween('createdAt', [$previousMonthStart, $previousMonthEnd]))
                    ->orWhereHas('produksis', fn ($q) => $q->whereBetween('createdAt', [$previousMonthStart, $previousMonthEnd]));
            })
            ->count();

        return $this->formatTrend($current, $previous);
    }

    protected function formatTrend(int $current, int $previous): array
    {
        if ($previous === 0) {
            $percent = $current > 0 ? 100.0 : 0.0;
        } else {
            $percent = (($current - $previous) / $previous) * 100;
        }

        $isPositive = $percent >= 0;

        return [
            'direction' => $isPositive ? 'up' : 'down',
            'label' => ($isPositive ? '+' : '') . number_format($percent, 2, ',', '.') . '%',
        ];
    }
}

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
        $totalJasa = Jasa::count();
        $totalPelanggan = Pelanggan::count();
        $totalActivity = $totalProduksi + $totalJasa;

        return [
            [
                'label' => 'Stepnosing',
                'value' => number_format($totalProduksi, 0, ',', '.'),
                'trend' => $this->calculateTrend(Produksi::class, 'createdAt'),
            ],
            [
                'label' => 'Jasa & Layanan',
                'value' => number_format($totalJasa, 0, ',', '.'),
                'trend' => $this->calculateTrend(Jasa::class, 'createdAt'),
            ],
            [
                'label' => 'Activity',
                'value' => number_format($totalActivity, 0, ',', '.'),
                'trend' => $this->calculateActivityTrend(),
            ],
            [
                'label' => 'Customers',
                'value' => number_format($totalPelanggan, 0, ',', '.'),
                'trend' => $this->calculateCustomerTrend(),
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
        $currentMonthCount = $this->countRecordsInMonth($model, $column, Carbon::now());
        $previousMonthCount = $this->countRecordsInMonth($model, $column, Carbon::now()->subMonth());

        return $this->formatTrend($currentMonthCount, $previousMonthCount);
    }

    protected function calculateCustomerTrend(): array
    {
        $currentMonthCount = $this->countRecordsInMonth(Pelanggan::class, 'createdAt', Carbon::now());
        $previousMonthCount = $this->countRecordsInMonth(Pelanggan::class, 'createdAt', Carbon::now()->subMonth());

        return $this->formatTrend($currentMonthCount, $previousMonthCount);
    }

    protected function calculateActivityTrend(): array
    {
        $currentMonthCount = $this->countActivityInMonth(Carbon::now());
        $previousMonthCount = $this->countActivityInMonth(Carbon::now()->subMonth());

        return $this->formatTrend($currentMonthCount, $previousMonthCount);
    }

    protected function countActivityInMonth(Carbon $month): int
    {
        return $this->countRecordsInMonth(Produksi::class, 'createdAt', $month)
            + $this->countRecordsInMonth(Jasa::class, 'createdAt', $month);
    }

    /**
     * @param class-string $model
     */
    protected function countRecordsInMonth(string $model, string $column, Carbon $month): int
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->isSameMonth(Carbon::now())
            ? Carbon::now()->endOfDay()
            : $month->copy()->endOfMonth();

        return $model::query()
            ->whereBetween($column, [$start, $end])
            ->count();
    }

    protected function formatTrend(int $currentMonthCount, int $previousMonthCount): array
    {
        if ($previousMonthCount === 0) {
            if ($currentMonthCount === 0) {
                return [
                    'direction' => 'up',
                    'label' => '0%',
                ];
            }

            return [
                'direction' => 'up',
                'label' => '+100%',
            ];
        }

        $percent = abs((($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100);
        $formattedPercent = number_format($percent, 2, ',', '.');

        if ($currentMonthCount > $previousMonthCount) {
            return [
                'direction' => 'up',
                'label' => '+' . $formattedPercent . '%',
            ];
        }

        if ($currentMonthCount < $previousMonthCount) {
            return [
                'direction' => 'down',
                'label' => '-' . $formattedPercent . '%',
            ];
        }

        return [
            'direction' => 'up',
            'label' => '0%',
        ];
    }
}

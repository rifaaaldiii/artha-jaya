<?php

namespace App\Filament\Widgets;

use App\Models\Jasa;
use App\Models\Produksi;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class ActivityChartWidget extends Widget
{
    protected string $view = 'filament.widgets.activity-chart-widget';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public string $filter = '3m';

    public function setFilter(string $filter): void
    {
        if (! in_array($filter, ['3m', '30d', '7d'], true)) {
            return;
        }

        $this->filter = $filter;
    }

    public function getSubtitleProperty(): string
    {
        return match ($this->filter) {
            '7d' => 'Total 7 hari terakhir',
            '30d' => 'Total 30 hari terakhir',
            default => 'Total 3 bulan terakhir',
        };
    }

    public function getChartDataProperty(): array
    {
        return match ($this->filter) {
            '7d' => $this->buildDailyChartData(Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()),
            '30d' => $this->buildDailyChartData(Carbon::now()->subDays(29)->startOfDay(), Carbon::now()->endOfDay()),
            default => $this->buildWeeklyChartData(Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()),
        };
    }

    #[On('aj-refresh-dashboard')]
    public function handleExternalRefresh(): void
    {
        $this->dispatch('$refresh');
    }

    protected function buildDailyChartData(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $produksi = [];
        $jasa = [];

        $current = $start->copy();

        while ($current->lte($end)) {
            $dayStart = $current->copy()->startOfDay();
            $dayEnd = $current->copy()->endOfDay();

            $labels[] = $current->format('M j');
            $produksi[] = $this->countProduksiBetween($dayStart, $dayEnd);
            $jasa[] = $this->countJasaBetween($dayStart, $dayEnd);

            $current->addDay();
        }

        return compact('labels', 'produksi', 'jasa');
    }

    protected function buildWeeklyChartData(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $produksi = [];
        $jasa = [];

        $current = $start->copy()->startOfWeek();

        while ($current->lte($end)) {
            $weekStart = $current->copy()->startOfDay();
            $weekEnd = $current->copy()->endOfWeek()->endOfDay();

            if ($weekEnd->gt($end)) {
                $weekEnd = $end->copy();
            }

            if ($weekStart->lt($start)) {
                $weekStart = $start->copy();
            }

            $labels[] = $weekStart->format('M j');
            $produksi[] = $this->countProduksiBetween($weekStart, $weekEnd);
            $jasa[] = $this->countJasaBetween($weekStart, $weekEnd);

            $current->addWeek();
        }

        return compact('labels', 'produksi', 'jasa');
    }

    protected function countProduksiBetween(Carbon $start, Carbon $end): int
    {
        return Produksi::query()
            ->whereBetween('createdAt', [$start, $end])
            ->count();
    }

    protected function countJasaBetween(Carbon $start, Carbon $end): int
    {
        return Jasa::query()
            ->whereBetween('createdAt', [$start, $end])
            ->count();
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\jasa;
use App\Models\produksi;
use App\Models\team;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ProductOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProduksi = produksi::count();
        $activeProduksi = produksi::where('status', '!=', 'selesai')->count();
        $completedProduksi = produksi::where('status', 'selesai')->count();

        $totalJasa = jasa::count();
        $scheduledJasa = jasa::where('status', '!=', 'selesai')->count();
        $completedJasa = jasa::where('status', 'selesai')->count();

        // Perbaikan untuk team
        $totalTeam = team::count();
        $activeTeam = team::where('status', 'ready')->count();
        $inactiveTeam = team::where('status', '!=', 'ready')->count();

        return [
            Stat::make('Produksi', number_format($totalProduksi, 0, ',', '.'))
                ->description("{$activeProduksi} sedang berjalan • {$completedProduksi} selesai")
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($this->buildMonthlyChart('produksi'))
                ->color('success'),
            Stat::make('Jasa & Layanan', number_format($totalJasa, 0, ',', '.'))
                ->description("{$scheduledJasa} terjadwal • {$completedJasa} selesai")
                ->descriptionIcon('heroicon-m-queue-list')
                ->chart($this->buildMonthlyChart('jasa'))
                ->color('info'),
            Stat::make('Team', number_format($totalTeam, 0, ',', '.'))
                ->description("{$activeTeam} Ready • {$inactiveTeam} Busy")
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($this->buildMonthlyChart('team'))
                ->color('warning'),
        ];
    }

    /**
     * @param 'produksi'|'jasa'|'team' $model
     */
    protected function buildMonthlyChart(string $model): array
    {
        if ($model === 'produksi') {
            $class = produksi::class;
            $column = 'createdAt';
        } elseif ($model === 'jasa') {
            $class = jasa::class;
            $column = 'createdAt';
        } elseif ($model === 'team') {
            $class = team::class;
            $column = 'createdAt';
        } else {
            return [];
        }

        $since = Carbon::now()->subMonths(5)->startOfMonth();

        $data = $class::query()
            ->where($column, '>=', $since)
            ->selectRaw("DATE_FORMAT({$column}, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total')
            ->toArray();

        return $this->padChart($data, 6);
    }

    protected function padChart(array $data, int $months): array
    {
        if (count($data) >= $months) {
            return array_slice($data, -$months);
        }

        return array_pad($data, $months, 0);
    }
}

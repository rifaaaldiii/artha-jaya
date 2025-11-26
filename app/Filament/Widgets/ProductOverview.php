<?php

namespace App\Filament\Widgets;

use App\Models\Jasa;
use App\Models\Produksi;
use App\Models\Team;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class ProductOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProduksi = Produksi::count();
        $activeProduksi = Produksi::where('status', '!=', 'selesai')->count();
        $completedProduksi = Produksi::where('status', 'selesai')->count();

        $totalJasa = Jasa::count();
        $scheduledJasa = Jasa::where('status', '!=', 'selesai')->count();
        $completedJasa = Jasa::where('status', 'selesai')->count();

        // Perbaikan untuk team
        $totalTeam = Team::count();
        $activeTeam = Team::where('status', 'ready')->count();
        $inactiveTeam = Team::where('status', '!=', 'ready')->count();

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

    #[On('aj-refresh-dashboard')]
    public function handleExternalRefresh(): void
    {
        $this->dispatch('$refresh');
    }

    /**
     * @param 'produksi'|'jasa'|'team' $model
     */
    protected function buildMonthlyChart(string $model): array
    {
        if ($model === 'produksi') {
            $class = Produksi::class;
            $column = 'createdAt';
        } elseif ($model === 'jasa') {
            $class = Jasa::class;
            $column = 'createdAt';
        } elseif ($model === 'team') {
            $class = Team::class;
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

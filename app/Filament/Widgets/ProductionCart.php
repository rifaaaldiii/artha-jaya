<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\produksi;
use App\Models\jasa;
use Illuminate\Support\Carbon;

class ProductionCart extends ChartWidget
{
    protected ?string $heading = 'Overview Produksi & Jasa';
    
    protected int | string | array $columnSpan = 'full-height';
    
    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $since = Carbon::now()->subMonths(5)->startOfMonth();
        $produksiData = produksi::query()
            ->where('createdAt', '>=', $since)
            ->selectRaw("DATE_FORMAT(createdAt, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Data Jasa per bulan (6 bulan terakhir)
        $jasaData = jasa::query()
            ->where('createdAt', '>=', $since)
            ->selectRaw("DATE_FORMAT(createdAt, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Generate labels untuk 6 bulan terakhir
        $labels = [];
        $produksiValues = [];
        $jasaValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthLabel = Carbon::now()->subMonths($i)->format('M Y');

            $labels[] = $monthLabel;
            $produksiValues[] = $produksiData[$month] ?? 0;
            $jasaValues[] = $jasaData[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Produksi',
                    'data' => $produksiValues,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Jasa & Layanan',
                    'data' => $jasaValues,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}

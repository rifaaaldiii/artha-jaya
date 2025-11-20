<?php

namespace App\Filament\Pages;

use App\Models\jasa;
use App\Models\petukang;
use App\Models\produksi;
use App\Models\team;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    protected function getViewData(): array
    {
        $timezone = 'Asia/Jakarta';
        $now = now($timezone);

        $totalProduksi = produksi::count();
        $activeProduksi = produksi::where('status', '!=', 'selesai')->count();
        $completedProduksi = produksi::where('status', 'selesai')->count();

        $totalJasa = jasa::count();
        $activeJasa = jasa::where('status', '!=', 'selesai')->count();

        $totalTeam = team::count();
        $totalPetukang = petukang::count();

        return [
            'timezone' => $timezone,
            'nowJakarta' => $now,
            'summaryCards' => [
                [
                    'label' => 'Produksi Aktif',
                    'value' => $activeProduksi,
                    'accent' => 'from-cyan-500 to-emerald-400',
                    'details' => "{$completedProduksi} selesai bulan ini",
                ],
                [
                    'label' => 'Jasa & Layanan',
                    'value' => $activeJasa,
                    'accent' => 'from-indigo-500 to-sky-400',
                    'details' => "{$totalJasa} total jadwal",
                ],
                [
                    'label' => 'Tim & Petukang',
                    'value' => $totalTeam . ' / ' . $totalPetukang,
                    'accent' => 'from-amber-500 to-orange-400',
                    'details' => 'Tim / Petukang terdaftar',
                ],
            ],
            'totals' => [
                'produksi' => $totalProduksi,
                'jasa' => $totalJasa,
                'team' => $totalTeam,
            ],
        ];
    }
}


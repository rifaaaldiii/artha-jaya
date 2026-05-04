<?php

namespace App\Filament\Widgets;

use App\Models\Jasa;
use App\Models\Produksi;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class ScheduleCalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.schedule-calendar-widget';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public int $currentMonth;
    public int $currentYear;
    public ?string $selectedDate = null;
    public array $scheduleDates = [];
    public array $detailItems = [];

    public function mount(): void
    {
        $now = now();
        $this->currentMonth = $now->month;
        $this->currentYear = $now->year;
        $this->loadScheduleDates();
    }

    public function loadScheduleDates(): void
    {
        $dates = [];

        $jasas = Jasa::query()
            ->whereNotNull('jadwal_petugas')
            ->select('id', 'no_jasa', 'jadwal_petugas', 'status', 'pelanggan_id', 'petugas_id')
            ->with(['pelanggan', 'petugas', 'petugasMany'])
            ->get();

        foreach ($jasas as $jasa) {
            $date = Carbon::parse($jasa->jadwal_petugas)->format('Y-m-d');
            if (!isset($dates[$date])) {
                $dates[$date] = ['jasa' => false, 'produksi' => false];
            }
            $dates[$date]['jasa'] = true;
        }

        $produksis = Produksi::query()
            ->whereNotNull('jadwal')
            ->select('id', 'no_produksi', 'jadwal', 'status', 'pelanggan_id', 'team_id', 'branch')
            ->with(['pelanggan', 'team'])
            ->get();

        foreach ($produksis as $produksi) {
            $date = Carbon::parse($produksi->jadwal)->format('Y-m-d');
            if (!isset($dates[$date])) {
                $dates[$date] = ['jasa' => false, 'produksi' => false];
            }
            $dates[$date]['produksi'] = true;
        }

        $this->scheduleDates = $dates;
    }

    public function previousMonth(): void
    {
        if ($this->currentMonth === 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
    }

    public function nextMonth(): void
    {
        if ($this->currentMonth === 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
    }

    public function goToToday(): void
    {
        $now = now();
        $this->currentMonth = $now->month;
        $this->currentYear = $now->year;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->loadDetailItems($date);
    }

    public function loadDetailItems(string $date): void
    {
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        $jasas = Jasa::query()
            ->whereBetween('jadwal_petugas', [$start, $end])
            ->with(['pelanggan', 'petugas', 'petugasMany'])
            ->get()
            ->map(fn (Jasa $jasa) => [
                'type' => 'jasa',
                'id' => $jasa->id,
                'number' => $jasa->no_jasa,
                'customer' => $jasa->pelanggan?->nama ?? '-',
                'status' => $jasa->status,
                'workers' => $jasa->petugasMany->pluck('nama')->join(', ') ?: ($jasa->petugas?->nama ?? '-'),
            ])
            ->toArray();

        $produksis = Produksi::query()
            ->whereBetween('jadwal', [$start, $end])
            ->with(['pelanggan', 'team'])
            ->get()
            ->map(fn (Produksi $produksi) => [
                'type' => 'produksi',
                'id' => $produksi->id,
                'number' => $produksi->no_produksi,
                'customer' => $produksi->pelanggan?->nama ?? '-',
                'status' => $produksi->status,
                'workers' => $produksi->team?->nama ?? ($produksi->branch ?? '-'),
            ])
            ->toArray();

        $this->detailItems = array_merge($jasas, $produksis);
    }

    public function getCalendarDays(): array
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startOfMonth = $date->copy()->startOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday
        $endOfMonth = $date->copy()->endOfMonth();

        // Adjust for Monday as first day (0 = Monday, 6 = Sunday)
        $startDayOfWeek = $startDayOfWeek === 0 ? 6 : $startDayOfWeek - 1;

        $days = [];

        // Empty cells before start of month
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $days[] = null;
        }

        // Days of month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($this->currentYear, $this->currentMonth, $day)->format('Y-m-d');
            $schedule = $this->scheduleDates[$currentDate] ?? null;

            $days[] = [
                'day' => $day,
                'date' => $currentDate,
                'isToday' => $currentDate === now()->format('Y-m-d'),
                'isSelected' => $this->selectedDate === $currentDate,
                'hasJasa' => $schedule['jasa'] ?? false,
                'hasProduksi' => $schedule['produksi'] ?? false,
            ];
        }

        return $days;
    }

    public function getMonthName(): string
    {
        return Carbon::create($this->currentYear, $this->currentMonth, 1)->locale('id')->translatedFormat('F Y');
    }

    public function getDayNames(): array
    {
        return ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    }
}

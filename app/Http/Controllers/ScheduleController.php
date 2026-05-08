<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $monthQuery = $request->query('month');
        $selectedDate = $request->query('date');

        if ($selectedDate) {
            try {
                $selectedDateCarbon = Carbon::parse($selectedDate);
                $selectedDate = $selectedDateCarbon->toDateString();
            } catch (\Throwable $e) {
                $selectedDate = null;
            }
        }

        if ($monthQuery) {
            try {
                $selectedMonth = Carbon::createFromFormat('Y-m', $monthQuery)->startOfMonth();
            } catch (\Throwable $e) {
                $selectedMonth = $today->copy()->startOfMonth();
            }
        } elseif (! empty($selectedDate)) {
            $selectedMonth = Carbon::parse($selectedDate)->startOfMonth();
        } else {
            $selectedMonth = $today->copy()->startOfMonth();
        }

        if (empty($selectedDate) || Carbon::parse($selectedDate)->format('Y-m') !== $selectedMonth->format('Y-m')) {
            if ($selectedMonth->format('Y-m') === $today->format('Y-m')) {
                $selectedDate = $today->toDateString();
            } else {
                $selectedDate = $selectedMonth->copy()->day(1)->toDateString();
            }
        }

        $produksis = Produksi::with(['pelanggan', 'team'])->get()->map(function ($p) {
            return [
                'type' => 'produksi',
                'id' => $p->id,
                'no_ref' => $p->no_ref,
                'jadwal' => $p->jadwal ? $p->jadwal->format('Y-m-d') : null,
                'status' => $p->status,
                'alamat' => $p->alamat,
                'catatan' => $p->catatan,
                'customer' => $p->pelanggan?->nama ?? '-',
                'workers' => $p->team?->nama ?? ($p->branch ?? '-'),
            ];
        });

        $jasas = Jasa::with(['pelanggan', 'petugas', 'petugasMany'])->get()->map(function ($j) {
            return [
                'type' => 'jasa',
                'id' => $j->id,
                'no_ref' => $j->no_ref,
                'jadwal' => $j->jadwal_petugas ? $j->jadwal_petugas->format('Y-m-d') : null,
                'status' => $j->status,
                'alamat' => $j->alamat,
                'catatan' => $j->catatan,
                'customer' => $j->pelanggan?->nama ?? '-',
                'workers' => $j->petugasMany->pluck('nama')->join(', ') ?: ($j->petugas?->nama ?? '-'),
            ];
        });

        $schedules = $produksis->concat($jasas)
            ->filter(fn ($s) => $s['jadwal'] !== null)
            ->sortBy('jadwal');

        $monthName = $selectedMonth->locale('id')->translatedFormat('F Y');
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $calendarDays = [];

        for ($i = 0; $i < $selectedMonth->dayOfWeek; $i++) {
            $calendarDays[] = null;
        }

        for ($day = 1; $day <= $selectedMonth->daysInMonth; $day++) {
            $date = $selectedMonth->copy()->day($day)->toDateString();
            $daySchedules = $schedules->where('jadwal', $date);

            $calendarDays[] = [
                'date' => $date,
                'day' => $day,
                'isSelected' => $date === $selectedDate,
                'isToday' => $date === $today->toDateString(),
                'hasJasa' => $daySchedules->contains('type', 'jasa'),
                'hasProduksi' => $daySchedules->contains('type', 'produksi'),
            ];
        }

        $detailItems = $schedules->where('jadwal', $selectedDate)->map(function ($item) {
            return [
                'type' => $item['type'],
                'number' => $item['no_ref'],
                'customer' => $item['customer'] ?? '-',
                'workers' => $item['workers'] ?? '-',
                'status' => $item['status'] ?? '-',
            ];
        })->values()->all();

        $prevMonth = $selectedMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $selectedMonth->copy()->addMonth()->format('Y-m');

        return view('public.schedule', compact(
            'monthName',
            'dayNames',
            'calendarDays',
            'selectedDate',
            'detailItems',
            'prevMonth',
            'nextMonth'
        ));
    }
}
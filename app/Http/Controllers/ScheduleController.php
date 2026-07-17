<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $monthQuery = $request->query('month');
        $dateQuery = $request->query('date');

        if ($monthQuery) {
            try {
                $currentMonth = Carbon::createFromFormat('Y-m', $monthQuery)->startOfMonth();
            } catch (\Throwable $e) {
                $currentMonth = $today->copy()->startOfMonth();
            }
        } elseif ($dateQuery) {
            try {
                $selectedDate = Carbon::parse($dateQuery);
                $currentMonth = $selectedDate->copy()->startOfMonth();
            } catch (\Throwable $e) {
                $currentMonth = $today->copy()->startOfMonth();
                $selectedDate = $today->copy();
            }
        } else {
            $currentMonth = $today->copy()->startOfMonth();
        }

        if (! isset($selectedDate)) {
            $selectedDate = $currentMonth->isSameMonth($today)
                ? $today->copy()
                : $currentMonth->copy();
        }

        $monthStart = $currentMonth->copy()->startOfMonth();
        $monthEnd = $currentMonth->copy()->endOfMonth();

        $schedules = Schedule::query()
            ->whereNotNull('jadwal_petugas')
            ->whereBetween('jadwal_petugas', [
                $monthStart->copy()->startOfDay(),
                $monthEnd->copy()->endOfDay(),
            ])
            ->orderBy('jadwal_petugas')
            ->get();

        $eventsByDate = [];
        foreach ($schedules as $schedule) {
            $dateKey = $schedule->jadwal_petugas->toDateString();
            $time = $schedule->jadwal_petugas->format('H:i');
            $keterangan = $schedule->keterangan ?: 'Tanpa keterangan';

            $eventsByDate[$dateKey][] = [
                'id' => $schedule->id,
                'status' => strtolower($schedule->status ?? 'terjadwal'),
                'status_label' => $schedule->status ?? 'Terjadwal',
                'title' => $time.' · '.$keterangan,
                'time' => $time,
                'keterangan' => $keterangan,
                'catatan' => $schedule->catatan,
                'location' => $schedule->alamat,
                'branch' => $schedule->branch,
                'pekerja' => $schedule->pekerja,
                'pic' => $schedule->pic,
            ];
        }

        $calendarDays = [];
        $startPad = $monthStart->dayOfWeek;

        for ($i = 0; $i < $startPad; $i++) {
            $calendarDays[] = null;
        }

        for ($day = 1; $day <= $monthEnd->day; $day++) {
            $date = $monthStart->copy()->day($day);
            $dateKey = $date->toDateString();

            $calendarDays[] = [
                'date' => $dateKey,
                'day' => $day,
                'isToday' => $date->isSameDay($today),
                'isSelected' => $date->isSameDay($selectedDate),
                'hasSchedule' => isset($eventsByDate[$dateKey]),
            ];
        }

        $weekStart = $selectedDate->copy()->startOfWeek(Carbon::SUNDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SATURDAY);
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dateKey = $date->toDateString();

            $weekDays[] = [
                'date' => $dateKey,
                'dayName' => $dayNames[$i],
                'dayNumber' => $date->day,
                'monthShort' => $date->locale('id')->translatedFormat('M'),
                'isToday' => $date->isSameDay($today),
                'items' => $eventsByDate[$dateKey] ?? [],
            ];
        }

        $stats = [
            'total' => $schedules->count(),
            'terjadwal' => $schedules->where('status', 'Terjadwal')->count(),
            'selesai' => $schedules->where('status', 'Selesai')->count(),
        ];

        $monthName = $currentMonth->locale('id')->translatedFormat('F Y');
        $weekLabel = $weekStart->locale('id')->translatedFormat('d M')
            .' – '
            .$weekEnd->locale('id')->translatedFormat('d M Y');
        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
        $prevWeek = $weekStart->copy()->subWeek()->toDateString();
        $nextWeek = $weekStart->copy()->addWeek()->toDateString();

        return view('public.schedule', compact(
            'calendarDays',
            'eventsByDate',
            'weekDays',
            'monthName',
            'weekLabel',
            'prevMonth',
            'nextMonth',
            'prevWeek',
            'nextWeek',
            'selectedDate',
            'stats',
            'today'
        ));
    }
}

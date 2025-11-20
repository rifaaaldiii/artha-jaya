<?php

namespace App\Filament\Widgets;

use Carbon\CarbonImmutable;
use Filament\Widgets\Widget;

class CalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.calendar-widget';

    protected function getViewData(): array
    {
        $timezone = 'Asia/Jakarta';
        $today = CarbonImmutable::now($timezone);

        $startOfMonth = $today->startOfMonth();
        $endOfMonth = $today->endOfMonth();

        $startOfCalendar = $startOfMonth->startOfWeek(CarbonImmutable::SUNDAY);
        $endOfCalendar = $endOfMonth->endOfWeek(CarbonImmutable::SATURDAY);

        $weeks = [];
        $current = $startOfCalendar;

        while ($current <= $endOfCalendar) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $week[] = [
                    'date' => $current,
                    'isCurrentMonth' => $current->month === $today->month,
                    'isToday' => $current->isSameDay($today),
                ];

                $current = $current->addDay();
            }

            $weeks[] = $week;
        }

        return [
            'timezone' => $timezone,
            'today' => $today,
            'weeks' => $weeks,
            'monthName' => $today->translatedFormat('F Y'),
            'dayNames' => collect(range(0, 6))
                ->map(fn (int $day) => CarbonImmutable::now()
                    ->startOfWeek(CarbonImmutable::SUNDAY)
                    ->addDays($day)
                    ->isoFormat('dd')
                )->toArray(),
        ];
    }
}


<div class="public-schedule-page">
    <div class="page-intro">
        <h1>Jadwal Produksi & Jasa</h1>
    </div>
    <style>
        :root {
            --sc-bg: #ffffff;
            --sc-bg-secondary: #f9fafb;
            --sc-bg-hover: #f3f4f6;
            --sc-bg-selected: #ecfdf5;
            --sc-bg-today: #fffbeb;
            --sc-border: #e5e7eb;
            --sc-border-light: #f3f4f6;
            --sc-text: #111827;
            --sc-text-secondary: #374151;
            --sc-text-muted: #6b7280;
            --sc-text-primary: #059669;
            --sc-text-warning: #d97706;
            --sc-ring: #10b981;
            --sc-shadow: rgba(0,0,0,0.08);
            --sc-jasa: #f59e0b;
            --sc-produksi: #3b82f6;
            --sc-both: #a855f7;
            --sc-success: #059669;
        }

        .dark,
        [data-theme="dark"] {
            --sc-bg: #111827;
            --sc-bg-secondary: #1f2937;
            --sc-bg-hover: #374151;
            --sc-bg-selected: rgba(16, 185, 129, 0.12);
            --sc-bg-today: rgba(245, 158, 11, 0.08);
            --sc-border: #374151;
            --sc-border-light: rgba(55, 65, 81, 0.5);
            --sc-text: #f9fafb;
            --sc-text-secondary: #e5e7eb;
            --sc-text-muted: #9ca3af;
            --sc-text-primary: #34d399;
            --sc-text-warning: #fbbf24;
            --sc-ring: #34d399;
            --sc-shadow: rgba(0,0,0,0.3);
            --sc-jasa: #fbbf24;
            --sc-produksi: #60a5fa;
            --sc-both: #c084fc;
            --sc-success: #34d399;
        }

        .public-schedule-page {
            min-height: 100vh;
            padding: 1.5rem 1rem 2rem;
            background: #f8fafc;
            color: var(--sc-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .page-intro {
            max-width: 820px;
            margin: 0 auto 1.75rem;
            text-align: center;
        }
        .page-intro h1 {
            margin: 0;
            font-size: clamp(1.9rem, 2.6vw, 2.5rem);
            font-weight: 700;
            color: var(--sc-text);
            letter-spacing: -0.02em;
        }
        .page-intro p {
            margin: 0.9rem auto 0;
            max-width: 40rem;
            color: var(--sc-text-secondary);
            line-height: 1.75;
        }

        .schedule-widget-wrapper {
            margin-bottom: 1rem;
        }

        .calendar-header-actions a,
        .calendar-header-actions button,
        .calendar-nav-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 40px;
            min-height: 40px;
            border-radius: 12px;
            background: var(--sc-bg-secondary);
            color: var(--sc-text-secondary);
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .calendar-header-actions a,
        .calendar-header-actions button {
            padding: 0 0.85rem;
        }

        .calendar-header-actions .btn-today {
            min-width: auto;
            padding: 0.65rem 1rem;
            font-weight: 600;
        }

        .calendar-header-actions a:hover,
        .calendar-header-actions button:hover,
        .calendar-nav-btn:hover {
            background: var(--sc-bg-hover);
            color: var(--sc-text);
            border-color: var(--sc-border);
            transform: translateY(-1px);
        }

        .calendar-nav-btn svg,
        .calendar-header-actions svg {
            width: 20px;
            height: 20px;
        }

        .calendar-day-names {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            border-bottom: 1px solid var(--sc-border);
            background: var(--sc-bg-secondary);
        }
        .calendar-day-names > div {
            padding: 12px 0;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--sc-text-muted);
            text-transform: uppercase;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            background: var(--sc-bg);
        }
        .calendar-day-empty {
            min-height: 88px;
            border-bottom: 1px solid var(--sc-border-light);
            border-right: 1px solid var(--sc-border-light);
            background: var(--sc-bg-secondary);
        }
        .calendar-day-empty:nth-child(7n) { border-right: none; }

        .calendar-day-btn {
            display: block;
            min-height: 80px;
            padding: 12px 10px;
            /* border: none; */
            border-radius: 12px;
            border-bottom: 1px solid var(--sc-border-light);
            border-right: 1px solid var(--sc-border-light);
            background: var(--sc-bg);
            color: var(--sc-text);
            text-align: left;
            cursor: pointer;
            transition: background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
            position: relative;
            font-family: inherit;
        }
        .calendar-day-btn:nth-child(7n) { border-right: none; }
        .calendar-day-btn:hover {
            background: var(--sc-bg-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        }

        .calendar-day-btn .day-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 999px;
            margin-bottom: 10px;
            background: var(--sc-bg-secondary);
            color: var(--sc-text-secondary);
            font-weight: 700;
        }

        .calendar-day-btn .day-indicators {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            line-height: 1;
            border: 1px solid red;
        }
        .calendar-day-btn .day-indicators .dot {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 46px;
            padding: 4px 8px;
            border-radius: 999px;
            color: #ffffff;
            font-size: 0.66rem;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.12);
            white-space: nowrap;
            border: 1px solid red;
            text-align: center;
        }
        .dot-jasa { background: var(--sc-jasa); }
        .dot-produksi { background: var(--sc-produksi); }
        .dot-both { background: var(--sc-both); }

        .calendar-day-btn.is-selected {
            background: rgba(239, 68, 68, 0.92);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(185, 28, 28, 0.9), 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        .calendar-day-btn.is-selected:hover {
            background: rgba(220, 38, 38, 0.92);
            transform: none;
        }
        .calendar-day-btn.is-selected .day-number {
            background: rgba(255,255,255,0.18);
            color: #fff;
            text-shadow: none;
        }
        .calendar-day-btn.is-selected .day-indicators .dot {
            box-shadow: 0 0 0 1px rgba(255,255,255,0.38);
        }

        .calendar-day-btn.is-today:not(.is-selected) {
            background: var(--sc-bg-today);
            box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.5);
        }

        .calendar-day-btn .day-number {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--sc-text-secondary);
        }
        .calendar-day-btn.is-today .day-number {
            color: var(--sc-text-primary);
        }
        .calendar-day-btn.has-schedule:not(.is-selected) .day-number {
            color: #b91c1c;
        }

        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 14px 16px;
            border-top: 1px solid var(--sc-border);
            background: var(--sc-bg-secondary);
        }
        .calendar-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.75);
            border: 1px solid rgba(148,163,184,0.25);
            font-size: 0.75rem;
            color: var(--sc-text-secondary);
        }
        .calendar-legend-item .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .schedule-widget-grid {
            display: grid;
            gap: 1rem;
        }
        @media (min-width: 1024px) {
            .schedule-widget-grid { grid-template-columns: 2fr 1fr; }
        }
        @media (max-width: 1023px) {
            .schedule-widget-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .calendar-header {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }
            .calendar-header-actions {
                justify-content: flex-end;
                flex-wrap: wrap;
            }
            .calendar-day-btn {
                min-height: 84px;
                padding: 10px 8px;
            }
            .calendar-day-names > div {
                padding: 8px 0;
                font-size: 0.7rem;
            }
            .calendar-legend {
                justify-content: flex-start;
            }
        }

        .schedule-card {
            background: var(--sc-bg);
            border-radius: 12px;
            border: 1px solid var(--sc-border);
            box-shadow: 0 1px 3px var(--sc-shadow);
            overflow: hidden;
        }
        .schedule-card-full {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid var(--sc-border);
        }
        .calendar-header h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--sc-text);
        }
        .calendar-header-actions {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .calendar-day-names {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            border-bottom: 1px solid var(--sc-border);
        }
        .calendar-day-names > div {
            padding: 8px 0;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--sc-text-muted);
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }
        .calendar-day-empty {
            min-height: 88px;
            border-bottom: 1px solid var(--sc-border-light);
            border-right: 1px solid var(--sc-border-light);
            background: var(--sc-bg-secondary);
        }
        .calendar-day-empty:nth-child(7n) { border-right: none; }

        /* Scheduled dates */
        .calendar-day-btn.has-schedule:not(.is-selected) {
            background: rgba(239, 68, 68, 0.12);
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.35);
        }
        .calendar-day-btn.has-schedule:not(.is-selected):hover {
            background: rgba(239, 68, 68, 0.22);
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.55);
        }
        .dark .calendar-day-btn.has-schedule:not(.is-selected),
        [data-theme="dark"] .calendar-day-btn.has-schedule:not(.is-selected) {
            background: rgba(239, 68, 68, 0.18);
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.45);
        }
        .dark .calendar-day-btn.has-schedule:not(.is-selected):hover,
        [data-theme="dark"] .calendar-day-btn.has-schedule:not(.is-selected):hover {
            background: rgba(239, 68, 68, 0.28);
            box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.65);
        }

        /* Selected date */
        .calendar-day-btn.is-selected {
            background: rgba(239, 68, 68, 0.88);
            box-shadow: inset 0 0 0 1px rgba(185, 28, 28, 0.9), 0 0 0 2px rgba(239, 68, 68, 0.25);
        }
        .calendar-day-btn.is-selected:hover {
            background: rgba(220, 38, 38, 0.92);
        }
        .dark .calendar-day-btn.is-selected,
        [data-theme="dark"] .calendar-day-btn.is-selected {
            background: rgba(239, 68, 68, 0.75);
            box-shadow: inset 0 0 0 1px rgba(185, 28, 28, 0.85), 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        .dark .calendar-day-btn.is-selected:hover,
        [data-theme="dark"] .calendar-day-btn.is-selected:hover {
            background: rgba(220, 38, 38, 0.85);
        }

        /* Today */
        .calendar-day-btn.is-today:not(.is-selected) {
            background: var(--sc-bg-today);
            box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.5);
        }

        /* Day number colors */
        .calendar-day-btn .day-number {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--sc-text-secondary);
        }
        .calendar-day-btn.is-today .day-number {
            color: var(--sc-text-primary);
            font-weight: 600;
        }
        .calendar-day-btn.is-selected .day-number {
            color: #ffffff;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.15);
        }
        .calendar-day-btn.has-schedule:not(.is-selected) .day-number {
            color: #991b1b;
            font-weight: 600;
        }
        .dark .calendar-day-btn.has-schedule:not(.is-selected) .day-number,
        [data-theme="dark"] .calendar-day-btn.has-schedule:not(.is-selected) .day-number {
            color: #fca5a5;
        }

        /* Day indicators */
        .calendar-day-btn .day-indicators {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            margin-top: 3px;
            flex-wrap: wrap;
            line-height: 1;
        }
        .calendar-day-btn .day-indicators .dot {
            display: inline-block;
            font-size: 0.6rem;
            font-weight: 600;
            padding: 1px 5px;
            border-radius: 999px;
            line-height: 1.3;
            white-space: nowrap;
            color: #ffffff;
            text-shadow: 0 1px 1px rgba(0,0,0,0.2);
        }
        .dot-jasa { background: var(--sc-jasa); }
        .dot-produksi { background: var(--sc-produksi); }
        .dot-both { background: var(--sc-both); }

        /* Day indicators on selected (white outline for contrast on red bg) */
        .calendar-day-btn.is-selected .day-indicators .dot {
            box-shadow: 0 0 0 1px rgba(255,255,255,0.5);
        }


        .calendar-legend {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 16px;
            border-top: 1px solid var(--sc-border);
            background: var(--sc-bg-secondary);
        }
        .calendar-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: var(--sc-text-muted);
        }
        .calendar-legend-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .detail-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--sc-border);
        }
        .detail-header h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--sc-text);
        }
        .detail-header p {
            margin: 2px 0 0;
            font-size: 0.75rem;
            color: var(--sc-text-muted);
        }

        .detail-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            /* max-height: 32rem; */
            background: var(--sc-bg-secondary);
            /* border: 1px solid red; */
        }
        .detail-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            text-align: center;
        }
        .detail-empty svg {
            width: 40px;
            height: 40px;
            color: var(--sc-text-muted);
            opacity: 0.6;
            margin-bottom: 8px;
        }
        .detail-empty p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--sc-text-muted);
        }

        .detail-item {
            border-radius: 12px;
            border: 1px solid var(--sc-border);
            padding: 16px;
            background: var(--sc-bg);
            margin-bottom: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }
        .detail-item:last-child { margin-bottom: 0; }
        .detail-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .detail-item-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
        }
        .detail-item-badge.jasa {
            background: rgba(245, 158, 11, 0.15);
            color: #b45309;
        }
        .dark .detail-item-badge.jasa,
        [data-theme="dark"] .detail-item-badge.jasa {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }
        .detail-item-badge.produksi {
            background: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
        }
        .dark .detail-item-badge.produksi,
        [data-theme="dark"] .detail-item-badge.produksi {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }
        .detail-item-number {
            font-size: 0.75rem;
            color: var(--sc-text-muted);
        }
        .detail-item-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .detail-item-row svg {
            width: 14px;
            height: 14px;
            color: var(--sc-text-muted);
            flex-shrink: 0;
        }
        .detail-item-row span {
            font-size: 0.85rem;
            color: var(--sc-text-secondary);
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
        }
        .detail-item-row .status-selesai {
            color: var(--sc-success);
        }
    </style>

    <div class="schedule-widget-wrapper">
        <div class="schedule-widget-grid">
            {{-- Calendar Section --}}
            <div>
                <div class="schedule-card">
                    <div class="calendar-header">
                        <h3>{{ $monthName }}</h3>
                        <div class="calendar-header-actions">
                            <a href="{{ route('public.schedule', ['date' => now()->toDateString()]) }}" class="btn-today">Hari Ini</a>
                            <a href="{{ route('public.schedule', ['month' => $prevMonth]) }}" class="calendar-nav-btn" aria-label="Bulan sebelumnya">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <a href="{{ route('public.schedule', ['month' => $nextMonth]) }}" class="calendar-nav-btn" aria-label="Bulan berikutnya">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="calendar-day-names">
                        @foreach ($dayNames as $dayName)
                            <div>{{ $dayName }}</div>
                        @endforeach
                    </div>

                    <div class="calendar-days">
                        @foreach ($calendarDays as $day)
                            @if ($day === null)
                                <div class="calendar-day-empty"></div>
                            @else
                                <a
                                    href="{{ route('public.schedule', ['date' => $day['date']]) }}"
                                    class="calendar-day-btn
                                        {{ $day['isSelected'] ? 'is-selected' : '' }}
                                        {{ !$day['isSelected'] && $day['isToday'] ? 'is-today' : '' }}
                                        {{ ($day['hasJasa'] || $day['hasProduksi']) ? 'has-schedule' : '' }}"
                                >
                                    <span class="day-number">{{ $day['day'] }}</span>
                                    <div class="day-indicators">
                                        @if ($day['hasJasa'] && $day['hasProduksi'])
                                            <span class="dot dot-both" title="Jasa & Produksi">Jasa & Produksi</span>
                                        @elseif ($day['hasJasa'])
                                            <span class="dot dot-jasa" title="Jasa">Jasa</span>
                                        @elseif ($day['hasProduksi'])
                                            <span class="dot dot-produksi" title="Produksi">Produksi</span>
                                        @endif
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>

                    <div class="calendar-legend">
                        <div class="calendar-legend-item">
                            <span class="dot dot-jasa"></span>
                            <span>Jasa</span>
                        </div>
                        <div class="calendar-legend-item">
                            <span class="dot dot-produksi"></span>
                            <span>Produksi</span>
                        </div>
                        <div class="calendar-legend-item">
                            <span class="dot dot-both"></span>
                            <span>Keduanya</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Panel --}}
            <div>
                <div class="schedule-card schedule-card-full">
                    <div class="detail-header">
                        <h3>Detail Jadwal</h3>
                        @if ($selectedDate)
                            <p>{{ \Illuminate\Support\Carbon::parse($selectedDate)->locale('id')->translatedFormat('l, d F Y') }}</p>
                        @endif
                    </div>

                    <div class="detail-body">
                        @if (!$selectedDate)
                            <div class="detail-empty">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p>Pilih tanggal pada kalender untuk melihat detail jadwal.</p>
                            </div>
                        @elseif (empty($detailItems))
                            <div class="detail-empty">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p>Tidak ada jadwal pada tanggal ini.</p>
                            </div>
                        @else
                            @foreach ($detailItems as $item)
                                <div class="detail-item">
                                    <div class="detail-item-header">
                                        <span class="detail-item-badge {{ $item['type'] === 'jasa' ? 'jasa' : 'produksi' }}">
                                            {{ $item['type'] === 'jasa' ? 'Jasa' : 'Produksi' }}
                                        </span>
                                        <span class="detail-item-number">{{ $item['number'] }}</span>
                                    </div>
                                    <div class="detail-item-row">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>{{ $item['customer'] }}</span>
                                    </div>
                                    <div class="detail-item-row">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>{{ $item['workers'] }}</span>
                                    </div>
                                    <div class="detail-item-row">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="{{ $item['status'] === 'selesai' || $item['status'] === 'selesai dikerjakan' ? 'status-selesai' : '' }}">
                                            {{ ucfirst($item['status']) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

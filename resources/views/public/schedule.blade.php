<div class="public-schedule-page">
    {{-- Set Page Title --}}
    <script>
        document.title = 'Jadwal Produksi & Jasa - Artha Jaya';
    </script>
    
    {{-- Page Header Section --}}
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <div class="page-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                        <h1>Jadwal Produksi & Jasa</h1>
                        <span class="live-indicator">
                            <span class="live-dot"></span>
                            Live
                        </span>
                    </div>
                    <p class="page-subtitle">Lihat jadwal layanan dan produksi Anda secara real-time</p>
                </div>
            </div>
            
            {{-- Summary Stats --}}
            <div class="summary-stats">
                <div class="stat-card stat-jasa">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['jasa'] ?? 0 }}</div>
                        <div class="stat-label">Total Jasa</div>
                    </div>
                </div>
                <div class="stat-card stat-produksi">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['produksi'] ?? 0 }}</div>
                        <div class="stat-label">Total Produksi</div>
                    </div>
                </div>
                <div class="stat-card stat-selesai">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['selesai'] ?? 0 }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
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

        body {
            zoom: 80%;
        }

        /* Page Header Styles */
        .page-header {
            /* max-width: 1200px; */
            margin: 0 auto 2rem;
        }
        .page-header-content {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border-radius: 20px;
            padding: 2rem;
            color: #ffffff;
            box-shadow: 0 20px 40px rgba(5, 150, 105, 0.2);
        }
        .dark .page-header-content,
        [data-theme="dark"] .page-header-content {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .page-title-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .page-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .page-icon svg {
            width: 28px;
            height: 28px;
        }
        .page-title-section h1 {
            margin: 0;
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .page-subtitle {
            margin: 0.25rem 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Live Indicator */
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg {
            width: 20px;
            height: 20px;
        }
        .stat-jasa .stat-icon { color: #f59e0b; }
        .stat-produksi .stat-icon { color: #3b82f6; }
        .stat-selesai .stat-icon { color: #10b981; }
        .stat-content {
            flex: 1;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .public-schedule-page {
            min-height: 100vh;
            padding: 1.5rem 1rem 2rem;
            background: linear-gradient(to bottom, #f0fdf4, #f8fafc);
            color: var(--sc-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .dark .public-schedule-page,
        [data-theme="dark"] .public-schedule-page {
            background: linear-gradient(to bottom, #0f172a, #1e293b);
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
            justify-content: center;
            gap: 4px;
            margin-top: 6px;
            line-height: 1;
        }
        .calendar-day-btn .day-indicators .dot {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            padding: 3px 8px;
            border-radius: 999px;
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.12);
            white-space: nowrap;
            text-align: center;
            transition: transform 0.2s ease;
        }
        .calendar-day-btn:hover .day-indicators .dot {
            transform: scale(1.05);
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

        /* .calendar-day-btn.is-today:not(.is-selected) {
            background: var(--sc-bg-today);
            box-shadow: inset 0 0 0 1px rgba(245, 158, 11, 0.5);
        } */

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
            .page-header-content {
                padding: 1.5rem;
            }
            .page-title-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            .summary-stats {
                grid-template-columns: 1fr;
            }
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
            .detail-header-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .schedule-card {
            background: var(--sc-bg);
            border-radius: 20px;
            border: 1px solid var(--sc-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .schedule-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
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
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--sc-border);
            background: linear-gradient(to right, var(--sc-bg), var(--sc-bg-secondary));
        }
        .calendar-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--sc-text);
            text-transform: capitalize;
        }
        .calendar-header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-today {
            background: linear-gradient(135deg, #059669, #10b981) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        .btn-today:hover {
            background: linear-gradient(135deg, #047857, #059669) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
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
            /* Removed today background and border */
        }

        /* Day number colors */
        .calendar-day-btn .day-number {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--sc-text-secondary);
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
            gap: 4px;
            margin-top: 4px;
            flex-wrap: wrap;
            line-height: 1;
        }
        .calendar-day-btn .day-indicators .dot {
            display: inline-block;
            font-size: 0.6rem;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 999px;
            line-height: 1.3;
            white-space: nowrap;
            color: #ffffff;
            text-shadow: 0 1px 1px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }
        .calendar-day-btn:hover .day-indicators .dot {
            transform: scale(1.08);
        }
        .dot-jasa { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .dot-produksi { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .dot-both { background: linear-gradient(135deg, #a855f7, #9333ea); }

        /* Day indicators on selected (white outline for contrast on red bg) */
        .calendar-day-btn.is-selected .day-indicators .dot {
            box-shadow: 0 0 0 1px rgba(255,255,255,0.5);
        }


        .calendar-legend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--sc-border);
            background: var(--sc-bg-secondary);
            flex-wrap: wrap;
        }
        .calendar-legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            background: var(--sc-bg);
            border: 1px solid var(--sc-border);
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--sc-text-secondary);
            transition: all 0.2s ease;
        }
        .calendar-legend-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }
        .calendar-legend-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .detail-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--sc-border);
            background: linear-gradient(to right, var(--sc-bg), var(--sc-bg-secondary));
        }
        .detail-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }
        .detail-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--sc-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .detail-header-icon {
            width: 20px;
            height: 20px;
            color: var(--sc-text-primary);
        }
        .selected-date-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--sc-text-primary);
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .selected-date-badge svg {
            width: 14px;
            height: 14px;
        }
        .detail-date-full {
            margin: 0;
            font-size: 0.8rem;
            color: var(--sc-text-muted);
        }

        .detail-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: var(--sc-bg-secondary);
        }
        .detail-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
            text-align: center;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .detail-empty svg {
            width: 48px;
            height: 48px;
            color: var(--sc-text-muted);
            opacity: 0.5;
            margin-bottom: 1rem;
        }
        .detail-empty p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--sc-text-muted);
            line-height: 1.6;
        }

        .detail-item {
            border-radius: 16px;
            border: 1px solid var(--sc-border);
            padding: 1.25rem;
            background: var(--sc-bg);
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.4s ease;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .detail-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        .detail-item.jasa-type::before {
            background: linear-gradient(to bottom, #f59e0b, #d97706);
        }
        .detail-item.produksi-type::before {
            background: linear-gradient(to bottom, #3b82f6, #2563eb);
        }
        .detail-item:hover {
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1);
            transform: translateY(-2px);
        }
        .detail-item:last-child { margin-bottom: 0; }
        .detail-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--sc-border-light);
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
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }
        .detail-item-badge.produksi {
            background: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
        }
        .dark .detail-item-badge.produksi,
        [data-theme="dark"] .detail-item-badge.produksi {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }
        .detail-item-number {
            font-size: 0.75rem;
            color: var(--sc-text-muted);
        }
        .detail-item-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 0.75rem;
            flex-wrap: wrap;
        }
        .detail-item-row svg {
            width: 16px;
            height: 16px;
            color: var(--sc-text-muted);
            flex-shrink: 0;
            margin-top: 2px;
        }
        .detail-item-row span {
            font-size: 0.875rem;
            color: var(--sc-text-secondary);
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
            line-height: 1.5;
        }
        .detail-item-row .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--sc-bg-secondary);
            color: var(--sc-text-muted);
        }
        .detail-item-row .status-badge.status-selesai {
            background: rgba(16, 185, 129, 0.1);
            color: var(--sc-success);
        }
        .detail-item-row .status-badge.status-proses {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        .detail-item-row .status-badge.status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
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
                        <div class="detail-header-top">
                            <h3>
                                <svg class="detail-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Detail Jadwal
                            </h3>
                            @if ($selectedDate)
                                <span class="selected-date-badge">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Illuminate\Support\Carbon::parse($selectedDate)->locale('id')->translatedFormat('d M Y') }}
                                </span>
                            @endif
                        </div>
                        <!-- @if ($selectedDate)
                            <p class="detail-date-full">{{ \Illuminate\Support\Carbon::parse($selectedDate)->locale('id')->translatedFormat('l, d F Y') }}</p>
                        @endif -->
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
                                <div class="detail-item {{ $item['type'] === 'jasa' ? 'jasa-type' : 'produksi-type' }}">
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
                                    @if (!empty($item['location']))
                                    <div class="detail-item-row">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $item['location'] }}</span>
                                    </div>
                                    @endif
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
                                        <span class="status-badge {{ $item['status'] === 'selesai' || $item['status'] === 'selesai dikerjakan' ? 'status-selesai' : '' }} {{ $item['status'] === 'proses' || $item['status'] === 'dikerjakan' ? 'status-proses' : '' }} {{ $item['status'] === 'pending' ? 'status-pending' : '' }}">
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

    {{-- Real-time Update Script --}}
    <script>
        (function() {
            const POLL_INTERVAL = 20000; // 30 seconds
            let pollTimer = null;
            let isUpdating = false;

            async function fetchScheduleData() {
                if (isUpdating) return;
                isUpdating = true;

                try {
                    const urlParams = new URLSearchParams(window.location.search);
                    const response = await fetch(window.location.pathname + '?' + urlParams.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });

                    if (!response.ok) {
                        isUpdating = false;
                        return;
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Add fade effect
                    const elementsToUpdate = [
                        { new: '.calendar-days', old: '.calendar-days' },
                        { new: '.detail-body', old: '.detail-body' },
                        { new: '.summary-stats', old: '.summary-stats' }
                    ];

                    elementsToUpdate.forEach(({ new: newSel, old: oldSel }) => {
                        const newEl = doc.querySelector(newSel);
                        const oldEl = document.querySelector(oldSel);
                        if (newEl && oldEl) {
                            oldEl.style.opacity = '0.5';
                            oldEl.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                oldEl.innerHTML = newEl.innerHTML;
                                oldEl.style.opacity = '1';
                            }, 300);
                        }
                    });

                    // Update month name
                    const newMonthName = doc.querySelector('.calendar-header h3');
                    const oldMonthName = document.querySelector('.calendar-header h3');
                    if (newMonthName && oldMonthName) {
                        oldMonthName.textContent = newMonthName.textContent;
                    }

                    // Update last update time
                    updateLastUpdateTime();
                    console.log('✓ Schedule updated at', new Date().toLocaleTimeString());
                } catch (error) {
                    console.error('✗ Failed to fetch schedule updates:', error);
                } finally {
                    isUpdating = false;
                }
            }

            function updateLastUpdateTime() {
                const now = new Date();
                const timeStr = now.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit'
                });
                
                let lastUpdateEl = document.querySelector('.last-update-time');
                if (!lastUpdateEl) {
                    lastUpdateEl = document.createElement('div');
                    lastUpdateEl.className = 'last-update-time';
                    lastUpdateEl.style.cssText = 'margin-top: 0.5rem; font-size: 0.7rem; opacity: 0.8; text-align: center;';
                    const headerContent = document.querySelector('.page-header-content');
                    if (headerContent) {
                        headerContent.appendChild(lastUpdateEl);
                    }
                }
                lastUpdateEl.textContent = `Last updated: ${timeStr}`;
            }

            // Start polling
            function startPolling() {
                updateLastUpdateTime();
                pollTimer = setInterval(fetchScheduleData, POLL_INTERVAL);
            }

            // Stop polling
            function stopPolling() {
                if (pollTimer) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                }
            }

            // Start when page loads
            startPolling();

            // Stop when page is hidden
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopPolling();
                } else {
                    startPolling();
                    fetchScheduleData(); // Fetch immediately when page becomes visible
                }
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', stopPolling);
        })();
    </script>
</div>

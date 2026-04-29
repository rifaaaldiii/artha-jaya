<x-filament-panels::page>
    {{-- Flatpickr CDN - MUST load FIRST before any custom styles --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    
    <style>
        :root {
            --aj-report-surface: #ffffff;
            --aj-report-card-bg: #ffffff;
            --aj-report-card-border: #e5e7eb;
            --aj-report-divider: #f3f4f6;
            --aj-report-text: #111827;
            --aj-report-muted: #6b7280;
            --aj-report-primary: #059669;
            --aj-report-primary-light: #d1fae5;
            --aj-report-primary-dark: #047857;
            --aj-report-accent: #10b981;
            --aj-report-soft-bg: #f8fafc;
            --aj-report-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
            --aj-report-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --aj-report-gradient: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        }

        .dark,
        [data-theme="dark"],
        .filament-theme-dark {
            --aj-report-surface: #0f172a;
            --aj-report-card-bg: #1e293b;
            --aj-report-card-border: #334155;
            --aj-report-divider: #1e293b;
            --aj-report-text: #f8fafc;
            --aj-report-muted: #94a3b8;
            --aj-report-primary: #34d399;
            --aj-report-primary-light: rgba(52, 211, 153, 0.15);
            --aj-report-primary-dark: #6ee7b7;
            --aj-report-accent: #10b981;
            --aj-report-soft-bg: #0f172a;
            --aj-report-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            --aj-report-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            --aj-report-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        /* Dark mode calendar specific styles */
        .dark #inline-calendar-container,
        [data-theme="dark"] #inline-calendar-container,
        .filament-theme-dark #inline-calendar-container {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months,
        [data-theme="dark"] #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months,
        .filament-theme-dark #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        
        .dark #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months:hover,
        [data-theme="dark"] #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months:hover,
        .filament-theme-dark #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: rgba(52, 211, 153, 0.15) !important;
            border-color: #34d399 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-current-month input.cur-year,
        [data-theme="dark"] #inline-calendar-container .flatpickr-current-month input.cur-year,
        .filament-theme-dark #inline-calendar-container .flatpickr-current-month input.cur-year {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        
        .dark #inline-calendar-container .flatpickr-current-month input.cur-year:hover,
        [data-theme="dark"] #inline-calendar-container .flatpickr-current-month input.cur-year:hover,
        .filament-theme-dark #inline-calendar-container .flatpickr-current-month input.cur-year:hover {
            background: rgba(52, 211, 153, 0.15) !important;
            border-color: #34d399 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-prev-month,
        .dark #inline-calendar-container .flatpickr-next-month,
        [data-theme="dark"] #inline-calendar-container .flatpickr-prev-month,
        [data-theme="dark"] #inline-calendar-container .flatpickr-next-month,
        .filament-theme-dark #inline-calendar-container .flatpickr-prev-month,
        .filament-theme-dark #inline-calendar-container .flatpickr-next-month {
            background: #0f172a !important;
            border-color: #334155 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-prev-month:hover,
        .dark #inline-calendar-container .flatpickr-next-month:hover,
        [data-theme="dark"] #inline-calendar-container .flatpickr-prev-month:hover,
        [data-theme="dark"] #inline-calendar-container .flatpickr-next-month:hover,
        .filament-theme-dark #inline-calendar-container .flatpickr-prev-month:hover,
        .filament-theme-dark #inline-calendar-container .flatpickr-next-month:hover {
            background: rgba(52, 211, 153, 0.15) !important;
            border-color: #34d399 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-day:hover,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day:hover,
        .filament-theme-dark #inline-calendar-container .flatpickr-day:hover {
            background: rgba(52, 211, 153, 0.2) !important;
            border-color: #34d399 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-day.today,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day.today,
        .filament-theme-dark #inline-calendar-container .flatpickr-day.today {
            background: rgba(52, 211, 153, 0.2) !important;
            border-color: #34d399 !important;
            color: #34d399 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-day.today:hover,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day.today:hover,
        .filament-theme-dark #inline-calendar-container .flatpickr-day.today:hover {
            background: #34d399 !important;
            color: #0f172a !important;
        }
        
        .dark #inline-calendar-container .flatpickr-day.selected,
        .dark #inline-calendar-container .flatpickr-day.startRange,
        .dark #inline-calendar-container .flatpickr-day.endRange,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day.selected,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day.startRange,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day.endRange,
        .filament-theme-dark #inline-calendar-container .flatpickr-day.selected,
        .filament-theme-dark #inline-calendar-container .flatpickr-day.startRange,
        .filament-theme-dark #inline-calendar-container .flatpickr-day.endRange {
            background: #34d399 !important;
            color: #0f172a !important;
            border-color: #34d399 !important;
        }
        
        .dark #inline-calendar-container .flatpickr-day.inRange,
        [data-theme="dark"] #inline-calendar-container .flatpickr-day.inRange,
        .filament-theme-dark #inline-calendar-container .flatpickr-day.inRange {
            background: rgba(52, 211, 153, 0.15) !important;
            border-color: rgba(52, 211, 153, 0.3) !important;
            color: #34d399 !important;
        }

        .report-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Filter Card */
        .report-filter-card {
            background: var(--aj-report-card-bg);
            border-radius: 12px;
            box-shadow: var(--aj-report-shadow);
            border: 1px solid var(--aj-report-card-border);
            padding: 24px;
            transition: all 0.2s ease;
        }

        .report-filter-card:hover {
            box-shadow: var(--aj-report-shadow-lg);
        }

        .report-filter-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--aj-report-divider);
        }

        .report-filter-icon {
            width: 40px;
            height: 40px;
            background: var(--aj-report-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--aj-report-primary);
        }

        .report-filter-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--aj-report-text);
            margin: 0;
        }

        .report-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--aj-report-divider);
        }

        /* Table Card */
        .report-table-card {
            background: var(--aj-report-card-bg);
            border-radius: 12px;
            box-shadow: var(--aj-report-shadow);
            border: 1px solid var(--aj-report-card-border);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .report-table-card:hover {
            box-shadow: var(--aj-report-shadow-lg);
        }

        .report-table-header {
            padding: 24px;
            background: var(--aj-report-gradient);
            border-bottom: 1px solid var(--aj-report-card-border);
        }

        .report-table-title-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .report-table-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--aj-report-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .report-table-title-icon {
            color: var(--aj-report-primary);
        }

        .report-table-subtitle {
            font-size: 14px;
            color: var(--aj-report-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .report-count-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: var(--aj-report-primary-light);
            color: var(--aj-report-primary-dark);
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
        }

        .report-period-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: var(--aj-report-soft-bg);
            color: var(--aj-report-muted);
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 500;
        }

        /* Search Input */
        .search-wrapper {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input-container {
            flex: 1;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--aj-report-muted);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            border: 2px solid var(--aj-report-card-border);
            border-radius: 10px;
            font-size: 14px;
            background: var(--aj-report-surface);
            color: var(--aj-report-text);
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--aj-report-primary);
            box-shadow: 0 0 0 3px var(--aj-report-primary-light);
        }

        .search-input::placeholder {
            color: var(--aj-report-muted);
        }

        .clear-search-btn {
            padding: 12px 20px;
            background: var(--aj-report-soft-bg);
            border: 2px solid var(--aj-report-card-border);
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-report-muted);
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .clear-search-btn:hover {
            background: var(--aj-report-card-border);
            color: var(--aj-report-text);
        }

        /* Table Container */
        .report-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table thead {
            background: var(--aj-report-soft-bg);
            border-bottom: 2px solid var(--aj-report-card-border);
        }

        .report-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--aj-report-muted);
            white-space: nowrap;
        }

        .report-table tbody tr {
            border-bottom: 1px solid var(--aj-report-divider);
            transition: all 0.15s ease;
        }

        .report-table tbody tr:hover {
            background: var(--aj-report-soft-bg);
        }

        .report-table tbody tr:last-child {
            border-bottom: none;
        }

        .report-table td {
            padding: 16px 20px;
            font-size: 14px;
            color: var(--aj-report-text);
        }

        .report-table .number-cell {
            font-weight: 600;
            color: var(--aj-report-text);
            font-family: 'Courier New', monospace;
        }

        .report-table .amount-cell {
            text-align: right;
            font-weight: 700;
            color: var(--aj-report-primary);
            font-family: 'Courier New', monospace;
            font-size: 15px;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            font-size: 12px;
            line-height: 20px;
            font-weight: 600;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-gray {
            background: #f3f4f6;
            color: #374151;
        }

        .dark .status-gray,
        [data-theme="dark"] .status-gray {
            background: #374151;
            color: #d1d5db;
        }

        .status-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .dark .status-blue,
        [data-theme="dark"] .status-blue {
            background: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
        }

        .status-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        .dark .status-yellow,
        [data-theme="dark"] .status-yellow {
            background: rgba(146, 64, 14, 0.3);
            color: #fcd34d;
        }

        .status-green {
            background: #d1fae5;
            color: #166534;
        }

        .dark .status-green,
        [data-theme="dark"] .status-green {
            background: rgba(22, 101, 52, 0.3);
            color: #86efac;
        }

        .status-purple {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .dark .status-purple,
        [data-theme="dark"] .status-purple {
            background: rgba(107, 33, 168, 0.3);
            color: #d8b4fe;
        }

        /* Action Button */
        .btn-invoice {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: var(--aj-report-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
        }

        .btn-invoice:hover {
            background: var(--aj-report-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        }

        .btn-invoice:active {
            transform: translateY(0);
        }

        .btn-invoice svg {
            width: 16px;
            height: 16px;
        }

        /* Pagination */
        .pagination-section {
            padding: 20px 24px;
            border-top: 2px solid var(--aj-report-divider);
            background: var(--aj-report-soft-bg);
        }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .pagination-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pagination-info {
            font-size: 14px;
            color: var(--aj-report-muted);
            font-weight: 500;
        }

        .pagination-info strong {
            color: var(--aj-report-text);
        }

        .per-page-select {
            padding: 8px 12px;
            border: 2px solid var(--aj-report-card-border);
            border-radius: 8px;
            font-size: 14px;
            background: var(--aj-report-surface);
            color: var(--aj-report-text);
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .per-page-select:hover {
            border-color: var(--aj-report-muted);
        }

        .per-page-select:focus {
            outline: none;
            border-color: var(--aj-report-primary);
            box-shadow: 0 0 0 3px var(--aj-report-primary-light);
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-pagination {
            padding: 8px 14px;
            background: var(--aj-report-surface);
            border: 2px solid var(--aj-report-card-border);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-report-text);
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 40px;
        }

        .btn-pagination:hover:not(:disabled) {
            background: var(--aj-report-soft-bg);
            border-color: var(--aj-report-muted);
        }

        .btn-pagination:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .btn-pagination.active {
            background: var(--aj-report-primary);
            border-color: var(--aj-report-primary);
            color: white;
            box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
        }

        /* Empty State */
        .empty-state {
            padding: 64px 24px;
            text-align: center;
        }

        .empty-state-icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--aj-report-soft-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon {
            width: 40px;
            height: 40px;
            color: var(--aj-report-muted);
        }

        .empty-state-text {
            font-size: 18px;
            font-weight: 600;
            color: var(--aj-report-text);
            margin: 0 0 8px;
        }

        .empty-state-hint {
            font-size: 14px;
            color: var(--aj-report-muted);
        }

        /* Utility Classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .report-table th,
            .report-table td {
                padding: 12px 16px;
                font-size: 13px;
            }

            .report-actions {
                flex-direction: column;
            }

            .report-actions button {
                width: 100%;
                justify-content: center;
            }
            
            #inline-calendar-container {
                padding: 16px;
            }
        }

        @media (max-width: 768px) {
            .report-page {
                gap: 16px;
            }

            .report-filter-card,
            .report-table-card {
                border-radius: 10px;
            }

            .report-filter-header {
                padding-bottom: 12px;
                margin-bottom: 16px;
            }

            .report-filter-icon {
                width: 36px;
                height: 36px;
            }

            .report-filter-title {
                font-size: 16px;
            }
            
            #inline-calendar-container {
                padding: 14px;
                border-radius: 10px;
            }
            
            #inline-calendar-container .flatpickr-current-month {
                font-size: 14px !important;
            }
            
            #inline-calendar-container .flatpickr-day {
                font-size: 13px !important;
            }

            .report-table-header {
                padding: 20px;
            }

            .report-table-title {
                font-size: 20px;
            }

            .report-table-subtitle {
                font-size: 13px;
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .search-wrapper {
                flex-direction: column;
            }

            .clear-search-btn {
                width: 100%;
            }

            .report-table-container {
                margin: 0;
            }

            .report-table {
                min-width: 900px;
            }

            .report-table th,
            .report-table td {
                padding: 12px 16px;
                font-size: 13px;
            }

            .pagination-wrapper {
                flex-direction: column;
                gap: 16px;
            }

            .pagination-left {
                width: 100%;
                justify-content: space-between;
            }

            .pagination-buttons {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn-invoice {
                padding: 6px 12px;
                font-size: 12px;
            }

            .btn-invoice svg {
                width: 14px;
                height: 14px;
            }
        }

        @media (max-width: 640px) {
            .report-filter-card {
                padding: 16px;
            }

            .report-table-header {
                padding: 16px;
            }

            .report-table-title {
                font-size: 18px;
            }

            .report-table-subtitle {
                font-size: 12px;
            }

            .report-table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            #inline-calendar-container {
                padding: 12px;
            }
            
            #inline-calendar-container .flatpickr-current-month {
                font-size: 13px !important;
            }
            
            #inline-calendar-container .flatpickr-day {
                font-size: 12px !important;
            }
            
            #inline-calendar-container .flatpickr-weekday {
                font-size: 11px !important;
            }

            .empty-state {
                padding: 48px 16px;
            }

            .empty-state-icon-wrapper {
                width: 64px;
                height: 64px;
            }

            .empty-state-icon {
                width: 32px;
                height: 32px;
            }

            .empty-state-text {
                font-size: 16px;
            }

            .empty-state-hint {
                font-size: 13px;
            }

            .pagination-section {
                padding: 16px;
            }

            .pagination-info {
                font-size: 13px;
            }
        }

        /* Better touch targets for mobile */
        @media (hover: none) and (pointer: coarse) {
            .btn-invoice {
                padding: 10px 14px;
                min-height: 44px;
            }

            .btn-pagination {
                min-height: 44px;
                padding: 10px 16px;
            }

            .clear-search-btn {
                min-height: 44px;
            }
        }
    </style>

    <style>
        /* ============================================
           FLATPICKR CUSTOM THEME - ARTHA JAYA
           ============================================ */
        
        /* RESET - Override all default Flatpickr styles */
        #inline-calendar-container .flatpickr-calendar,
        #inline-calendar-container .flatpickr-calendar * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Remove ALL default Flatpickr backgrounds and borders */
        #inline-calendar-container .flatpickr-calendar {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        #inline-calendar-container .flatpickr-innerContainer {
            background: transparent !important;
            border: none !important;
        }
        
        #inline-calendar-container .flatpickr-months {
            background: transparent !important;
        }
        
        #inline-calendar-container .flatpickr-month {
            background: transparent !important;
        }
        
        /* Filter Card Content */
        .report-filter-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        /* Calendar Section */
        .calendar-section {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .calendar-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-report-text);
        }
        
        .calendar-label svg {
            flex-shrink: 0;
            color: var(--aj-report-primary);
        }
        
        /* Inline Calendar Container */
        #inline-calendar-container {
            width: 100%;
            background: var(--aj-report-surface);
            border: 2px solid var(--aj-report-card-border);
            border-radius: 12px;
            padding: 20px;
            overflow: hidden;
            transition: all 0.2s ease;
            box-shadow: var(--aj-report-shadow);
        }
        
        #inline-calendar-container:hover {
            border-color: var(--aj-report-primary);
            box-shadow: 0 0 0 3px var(--aj-report-primary-light), var(--aj-report-shadow-lg);
        }
        
        /* Flatpickr Calendar Base */
        #inline-calendar-container .flatpickr-calendar {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            background: transparent !important;
            font-family: inherit !important;
            font-size: 14px !important;
            overflow: hidden !important;
            position: relative !important;
        }
        
        #inline-calendar-container .flatpickr-calendar * {
            box-sizing: border-box !important;
        }
        
        #inline-calendar-container .flatpickr-calendar.inline {
            display: block !important;
            position: relative !important;
            top: 0 !important;
        }
        
        #inline-calendar-container .flatpickr-days {
            width: 100% !important;
            position: relative !important;
        }
        
        #inline-calendar-container .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            justify-content: space-between !important;
            padding: 0 !important;
            display: flex !important;
            flex-wrap: wrap !important;
        }
        
        #inline-calendar-container .flatpickr-day {
            max-width: 100% !important;
            border-radius: 10px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            height: 40px !important;
            line-height: 36px !important;
            margin: 2px !important;
            flex: 0 0 calc(14.285% - 4px) !important;
        }
        
        /* Flatpickr Header Styling */
        #inline-calendar-container .flatpickr-current-month {
            font-size: 16px !important;
            font-weight: 700 !important;
            color: var(--aj-report-text) !important;
            padding: 8px 0 !important;
            height: auto !important;
            line-height: 1.5 !important;
        }
        
        #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
            font-size: 16px !important;
            color: var(--aj-report-text) !important;
            background: var(--aj-report-soft-bg) !important;
            border: 2px solid var(--aj-report-card-border) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            margin-right: 8px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 8px center !important;
            background-repeat: no-repeat !important;
            background-size: 16px !important;
            padding-right: 32px !important;
        }
        
        #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            border-color: var(--aj-report-primary) !important;
            background: var(--aj-report-primary-light) !important;
        }
        
        #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months:focus {
            outline: none !important;
            border-color: var(--aj-report-primary) !important;
            box-shadow: 0 0 0 3px var(--aj-report-primary-light) !important;
        }
        
        #inline-calendar-container .flatpickr-current-month input.cur-year {
            font-weight: 700 !important;
            font-size: 16px !important;
            color: var(--aj-report-text) !important;
            background: var(--aj-report-soft-bg) !important;
            border: 2px solid var(--aj-report-card-border) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }
        
        #inline-calendar-container .flatpickr-current-month input.cur-year:hover {
            border-color: var(--aj-report-primary) !important;
            background: var(--aj-report-primary-light) !important;
        }
        
        #inline-calendar-container .flatpickr-current-month input.cur-year:focus {
            outline: none !important;
            border-color: var(--aj-report-primary) !important;
            box-shadow: 0 0 0 3px var(--aj-report-primary-light) !important;
        }
        
        /* Navigation Arrows */
        #inline-calendar-container .flatpickr-prev-month,
        #inline-calendar-container .flatpickr-next-month {
            fill: var(--aj-report-primary) !important;
            color: var(--aj-report-primary) !important;
            transition: all 0.2s ease !important;
            width: 36px !important;
            height: 36px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 8px !important;
            background: var(--aj-report-soft-bg) !important;
            border: 2px solid var(--aj-report-card-border) !important;
            padding: 0 !important;
            top: 8px !important;
        }
        
        #inline-calendar-container .flatpickr-prev-month:hover,
        #inline-calendar-container .flatpickr-next-month:hover {
            fill: var(--aj-report-primary-dark) !important;
            color: var(--aj-report-primary-dark) !important;
            background: var(--aj-report-primary-light) !important;
            border-color: var(--aj-report-primary) !important;
            transform: scale(1.05) !important;
        }
        
        #inline-calendar-container .flatpickr-prev-month svg,
        #inline-calendar-container .flatpickr-next-month svg {
            width: 20px !important;
            height: 20px !important;
        }
        
        /* Weekday Headers */
        #inline-calendar-container .flatpickr-weekdays {
            margin-bottom: 12px !important;
            padding-bottom: 12px !important;
            border-bottom: 2px solid var(--aj-report-divider) !important;
        }
        
        #inline-calendar-container .flatpickr-weekday {
            font-weight: 700 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: var(--aj-report-muted) !important;
            padding: 8px 0 !important;
        }
        
        /* Day Styling */
        #inline-calendar-container .flatpickr-day {
            color: var(--aj-report-text) !important;
            border: 2px solid transparent !important;
            border-radius: 10px !important;
            height: 40px !important;
            line-height: 36px !important;
            margin: 2px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }
        
        #inline-calendar-container .flatpickr-day:hover {
            background: var(--aj-report-primary-light) !important;
            color: var(--aj-report-primary) !important;
            border-color: var(--aj-report-primary) !important;
            transform: scale(1.05) !important;
        }
        
        #inline-calendar-container .flatpickr-day.today {
            border-color: var(--aj-report-primary) !important;
            background: var(--aj-report-primary-light) !important;
            color: var(--aj-report-primary) !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.2) !important;
        }
        
        #inline-calendar-container .flatpickr-day.today:hover {
            background: var(--aj-report-primary) !important;
            color: white !important;
            transform: scale(1.05) !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3) !important;
        }
        
        /* Selected Range Styling */
        #inline-calendar-container .flatpickr-day.selected,
        #inline-calendar-container .flatpickr-day.startRange,
        #inline-calendar-container .flatpickr-day.endRange {
            background: var(--aj-report-primary) !important;
            color: white !important;
            border-color: var(--aj-report-primary) !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4) !important;
            transform: scale(1.05) !important;
        }
        
        #inline-calendar-container .flatpickr-day.selected:hover,
        #inline-calendar-container .flatpickr-day.startRange:hover,
        #inline-calendar-container .flatpickr-day.endRange:hover {
            background: var(--aj-report-primary-dark) !important;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.5) !important;
        }
        
        #inline-calendar-container .flatpickr-day.inRange {
            background: var(--aj-report-primary-light) !important;
            color: var(--aj-report-primary) !important;
            border-color: var(--aj-report-primary-light) !important;
            box-shadow: none !important;
            border-radius: 10px !important;
        }
        
        #inline-calendar-container .flatpickr-day.inRange:hover {
            background: rgba(5, 150, 105, 0.2) !important;
            border-color: var(--aj-report-primary) !important;
        }
        
        #inline-calendar-container .flatpickr-day.startRange {
            border-radius: 10px 0 0 10px !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4) !important;
        }
        
        #inline-calendar-container .flatpickr-day.endRange {
            border-radius: 0 10px 10px 0 !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4) !important;
        }
        
        /* Disabled/Other Month Days */
        #inline-calendar-container .flatpickr-day.prevMonthDay,
        #inline-calendar-container .flatpickr-day.nextMonthDay {
            color: var(--aj-report-muted) !important;
            opacity: 0.4 !important;
        }
        
        #inline-calendar-container .flatpickr-day.disabled {
            color: var(--aj-report-muted) !important;
            opacity: 0.3 !important;
            cursor: not-allowed !important;
        }
        
        /* Flatpickr Wrapper */
        #inline-calendar-container .flatpickr-wrapper {
            width: 100% !important;
        }
        
        /* Month Navigation */
        #inline-calendar-container .flatpickr-months {
            margin-bottom: 16px !important;
            padding-bottom: 12px !important;
            border-bottom: 2px solid var(--aj-report-divider) !important;
        }
        
        #inline-calendar-container .flatpickr-month {
            height: 40px !important;
        }
        
        /* Days Container */
        #inline-calendar-container .flatpickr-days {
            width: 100% !important;
        }
        
        #inline-calendar-container .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            justify-content: space-between !important;
            padding: 0 !important;
        }
        
        /* Animations */
        #inline-calendar-container .flatpickr-day {
            animation: fadeIn 0.2s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Report Actions */
        .report-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--aj-report-divider);
        }
        
        .report-actions button {
            flex: 1;
            min-width: 140px;
            justify-content: center;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .report-filter-card {
                padding: 20px !important;
            }
            
            .report-filter-content {
                gap: 20px;
            }
            
            #inline-calendar-container {
                width: 100%;
                padding: 16px;
                border-radius: 10px;
            }
            
            #inline-calendar-container .flatpickr-calendar {
                max-width: 100% !important;
            }
            
            #inline-calendar-container .flatpickr-current-month {
                font-size: 14px !important;
            }
            
            #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months,
            #inline-calendar-container .flatpickr-current-month input.cur-year {
                font-size: 14px !important;
                padding: 5px 10px !important;
            }
            
            #inline-calendar-container .flatpickr-day {
                font-size: 13px !important;
                height: 36px !important;
                line-height: 32px !important;
                margin: 1px !important;
            }
            
            #inline-calendar-container .flatpickr-prev-month,
            #inline-calendar-container .flatpickr-next-month {
                width: 32px !important;
                height: 32px !important;
            }
            
            .report-actions {
                flex-direction: column;
            }
            
            .report-actions button {
                width: 100%;
                min-width: auto;
            }
        }
        
        @media (max-width: 480px) {
            .report-filter-card {
                padding: 16px !important;
            }
            
            .calendar-label {
                font-size: 13px;
            }
            
            #inline-calendar-container {
                padding: 12px;
            }
            
            #inline-calendar-container .flatpickr-current-month {
                font-size: 13px !important;
            }
            
            #inline-calendar-container .flatpickr-current-month .flatpickr-monthDropdown-months,
            #inline-calendar-container .flatpickr-current-month input.cur-year {
                font-size: 13px !important;
                padding: 4px 8px !important;
            }
            
            #inline-calendar-container .flatpickr-day {
                font-size: 12px !important;
                height: 32px !important;
                line-height: 28px !important;
                margin: 1px !important;
            }
            
            #inline-calendar-container .flatpickr-weekday {
                font-size: 11px !important;
            }
            
            #inline-calendar-container .flatpickr-prev-month,
            #inline-calendar-container .flatpickr-next-month {
                width: 28px !important;
                height: 28px !important;
            }
        }
    </style>

    <div class="report-page">
        {{-- Filter Section --}}
        <div class="report-filter-card">
            <div class="report-filter-header">
                <div class="report-filter-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                </div>
                <h2 class="report-filter-title">Filter Laporan</h2>
            </div>
            
            <div class="report-filter-content">
                {{ $this->filterForm }}
                
                {{-- Inline Date Range Calendar --}}
                <div class="calendar-section">
                    <label class="calendar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Pilih Rentang Tanggal
                    </label>
                    <div id="inline-calendar-container"></div>
                </div>
            </div>
            
            <div class="report-actions">
                <x-filament::button color="gray" wire:click="resetFilters">
                    <x-filament::icon icon="heroicon-m-arrow-path" class="w-4 h-4 mr-1" />
                    Reset Filter
                </x-filament::button>
                
                <x-filament::button 
                    color="success" 
                    wire:click="downloadFilteredExcel"
                    :disabled="$resultCount === 0">
                    <x-filament::icon icon="heroicon-m-arrow-down-tray" class="w-4 h-4 mr-1" />
                    Download Excel
                </x-filament::button>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="report-table-card">
            <div class="report-table-header">
                <div class="report-table-title-section">
                    <h2 class="report-table-title">
                        <svg class="report-table-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            @if($filters['report_type'] === 'jasa')
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                            @else
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            @endif
                        </svg>
                        @if($filters['report_type'] === 'jasa')
                            Data Jasa & Layanan
                        @else
                            Data Produksi
                        @endif
                    </h2>
                    <p class="report-table-subtitle">
                        <span class="report-count-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            Total: {{ $resultCount }} record
                        </span>
                        @if($filters['start_date'] || $filters['end_date'])
                            <span class="report-period-badge"
                            style="gap: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                @php
                                    $startDate = $filters['start_date'] ?? null;
                                    $endDate = $filters['end_date'] ?? null;
                                                                
                                    $formatDate = function($date) {
                                        if (!$date) return null;
                                        try {
                                            $dt = \Carbon\Carbon::parse($date);
                                            return $dt->locale('id')->isoFormat('D MMMM YYYY');
                                        } catch (\Exception $e) {
                                            return $date;
                                        }
                                    };
                                                                
                                    $formattedStart = $formatDate($startDate);
                                    $formattedEnd = $formatDate($endDate);
                                @endphp
                                {{ $formattedStart ?? 'Awal' }} - {{ $formattedEnd ?? 'Akhir' }}
                            </span>
                        @endif
                    </p>
                </div>
                
                {{-- Search Input --}}
                <div class="search-wrapper">
                    <div class="search-input-container">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text"
                               wire:model.live.debounce.300ms="searchQuery"
                               placeholder="Cari no, pelanggan, status..."
                               class="search-input">
                    </div>
                    @if(!empty($searchQuery))
                    <button wire:click="$set('searchQuery', ''); loadPreviewData()"
                            class="clear-search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 6px;">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Clear
                    </button>
                    @endif
                </div>
            </div>

            @if(count($previewRows) > 0)
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            
                            @if($filters['report_type'] === 'jasa')
                            <th>No Jasa</th>
                            <th>No Ref</th>
                            <th>Pelanggan</th>
                            <th>Petugas</th>
                            @else
                            <th>No Produksi</th>
                            <th>No Ref</th>
                            <th>Branch</th>
                            <th>Team</th>
                            @endif
                            
                            <th>Status</th>
                            <th class="text-right">Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewRows as $index => $row)
                        <tr>
                            <td class="whitespace-nowrap">
                                {{ (($currentPage - 1) * $perPage) + $index + 1 }}
                            </td>
                            
                            @if($filters['report_type'] === 'jasa')
                            <td class="whitespace-nowrap number-cell">
                                {{ $row['number'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['no_ref'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['customer'] ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['petugas'] ?? '-' }}
                            </td>
                            @else
                            <td class="whitespace-nowrap number-cell">
                                {{ $row['number'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['no_ref'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['branch'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['team'] ?? '-' }}
                            </td>
                            @endif
                            
                            <td class="whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'jasa baru' => 'status-gray',
                                        'terjadwal' => 'status-blue',
                                        'selesai dikerjakan' => 'status-yellow',
                                        'selesai' => 'status-green',
                                        'baru' => 'status-gray',
                                        'produksi baru' => 'status-gray',
                                        'proses' => 'status-yellow',
                                        'siap produksi' => 'status-yellow',
                                        'dalam pengerjaan' => 'status-yellow',
                                        'siap diambil' => 'status-purple',
                                        'produksi siap diambil' => 'status-purple',
                                    ];
                                    
                                    $statusLabels = [
                                        'produksi baru' => 'Baru',
                                        'siap produksi' => 'Proses',
                                        'dalam pengerjaan' => 'Proses',
                                        'produksi siap diambil' => 'Siap Diambil',
                                        'selesai dikerjakan' => 'Selesai',
                                    ];
                                    
                                    $statusClass = $statusColors[$row['status']] ?? 'status-gray';
                                    $displayStatus = $statusLabels[$row['status']] ?? $row['status'];
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $displayStatus }}
                                </span>
                            </td>
                            
                            <td class="whitespace-nowrap amount-cell">
                                Rp {{ number_format($row['total_harga'], 0, ',', '.') }}
                            </td>
                            
                            <td class="whitespace-nowrap">
                                {{ $row['created_at'] }}
                            </td>
                            
                            <td class="whitespace-nowrap text-center">
                                <a href="/admin/report/preview-invoice?number={{ urlencode($row['number']) }}&type={{ urlencode($filters['report_type']) }}"
                                   target="_blank"
                                   class="btn-invoice">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @php
                $totalPages = ceil($resultCount / $perPage);
            @endphp
            
            @if($totalPages > 1)
            <div class="pagination-section">
                <div class="pagination-wrapper">
                    <div class="pagination-left">
                        <div class="pagination-info">
                            Menampilkan <strong>{{ (($currentPage - 1) * $perPage) + 1 }}</strong> - <strong>{{ min($currentPage * $perPage, $resultCount) }}</strong> dari <strong>{{ $resultCount }}</strong> data
                        </div>
                        
                        <select wire:change="$set('perPage', parseInt($event.target.value)); $wire.currentPage = 1; $wire.loadPreviewData()"
                                class="per-page-select">
                            <option value="10" {{ $perPage === 10 ? 'selected' : '' }}>10 per halaman</option>
                            <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25 per halaman</option>
                            <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50 per halaman</option>
                            <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100 per halaman</option>
                        </select>
                    </div>
                    
                    <div class="pagination-buttons">
                        <x-filament::button size="sm" color="gray" wire:click="previousPage" :disabled="$currentPage <= 1">
                            <x-filament::icon icon="heroicon-m-chevron-left" class="w-4 h-4" />
                            Previous
                        </x-filament::button>
                        
                        @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        <x-filament::button 
                            size="sm" 
                            :color="$i === $currentPage ? 'success' : 'gray'"
                            wire:click="goToPage({{ $i }})">
                            {{ $i }}
                        </x-filament::button>
                        @endfor
                        
                        <x-filament::button size="sm" color="gray" wire:click="nextPage" :disabled="$currentPage >= $totalPages">
                            Next
                            <x-filament::icon icon="heroicon-m-chevron-right" class="w-4 h-4" />
                        </x-filament::button>
                    </div>
                </div>
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-state-icon-wrapper">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="empty-state-text">Tidak ada data untuk ditampilkan</p>
                <p class="empty-state-hint">Coba ubah filter atau rentang tanggal</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Date Range Picker Script --}}
    <script>
        let flatpickrInstance = null;
        let selectedDates = [];

        document.addEventListener('livewire:initialized', function () {
            console.log('Livewire initialized, setting up listener');
            
            // Listen for Livewire updates
            Livewire.hook('commit', ({ component, succeed }) => {
                succeed(() => {
                    console.log('✓ Livewire update completed, checking calendar...');
                    setTimeout(() => {
                        reinitializeCalendarIfNeeded();
                    }, 100);
                });
            });
            
            // Listen for destroy-calendar event from resetFilters
            Livewire.on('destroy-calendar', () => {
                console.log('🗑️ Destroying calendar instance...');
                if (flatpickrInstance) {
                    try {
                        flatpickrInstance.destroy();
                        console.log('✓ Calendar destroyed successfully');
                    } catch (e) {
                        console.error('Error destroying calendar:', e);
                    }
                    flatpickrInstance = null;
                }
                
                // Clear selected dates
                selectedDates = [];
                
                // Clear the calendar container content
                const calendarContainer = document.getElementById('inline-calendar-container');
                if (calendarContainer) {
                    calendarContainer.innerHTML = '';
                    console.log('✓ Calendar container cleared');
                }
                
                // Reinitialize calendar after a short delay
                setTimeout(() => {
                    console.log('🔄 Re-initializing calendar...');
                    initDateRangePicker();
                }, 200);
            });
            
            setTimeout(() => {
                initDateRangePicker();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                initDateRangePicker();
            }, 500);
        });

        function reinitializeCalendarIfNeeded() {
            const calendarContainer = document.getElementById('inline-calendar-container');
            
            if (!calendarContainer) {
                console.log('❌ Calendar container not found for re-initialization');
                return;
            }
            
            // Check if calendar element exists
            const calendarElement = calendarContainer.querySelector('.flatpickr-calendar');
            
            if (!calendarElement) {
                console.log('⚠️ Calendar element missing, re-initializing...');
                initDateRangePicker();
            } else {
                console.log('✓ Calendar element still exists, no re-initialization needed');
            }
        }

        function initDateRangePicker() {
            const calendarContainer = document.getElementById('inline-calendar-container');
            const dateDisplay = document.getElementById('selected-date-display');
            
            if (!calendarContainer) {
                console.log('❌ Calendar container not found');
                return;
            }
            
            // Destroy existing instance if exists
            if (flatpickrInstance) {
                try {
                    flatpickrInstance.destroy();
                } catch (e) {
                    // Ignore errors
                }
                flatpickrInstance = null;
            }
            
            // Initialize Inline Flatpickr with previously selected dates if any
            try {
                flatpickrInstance = flatpickr(calendarContainer, {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    altInput: false,
                    inline: true,
                    locale: "id",
                    conjunction: " - ",
                    allowInput: false,
                    disableMobile: false,
                    defaultDate: selectedDates.length === 2 ? selectedDates : null,
                    onChange: function(selectedDatesArg, dateStr, instance) {
                        selectedDates = selectedDatesArg; // Save selected dates
                        
                        // Update display
                        if (selectedDatesArg.length === 2) {
                            // Use flatpickr's formatDate to avoid timezone issues
                            const startDate = instance.formatDate(selectedDatesArg[0], 'Y-m-d');
                            const endDate = instance.formatDate(selectedDatesArg[1], 'Y-m-d');
                            
                            // Update display text
                            if (dateDisplay) {
                                const startFormatted = selectedDatesArg[0].toLocaleDateString('id-ID', { 
                                    day: 'numeric', 
                                    month: 'short', 
                                    year: 'numeric' 
                                });
                                const endFormatted = selectedDatesArg[1].toLocaleDateString('id-ID', { 
                                    day: 'numeric', 
                                    month: 'short', 
                                    year: 'numeric' 
                                });
                                dateDisplay.innerHTML = `
                                    <span style="color: var(--aj-report-primary); font-weight: 600;">
                                        ✓ ${startFormatted} - ${endFormatted}
                                    </span>
                                `;
                            }
                            
                            // Update Livewire
                            const wireElement = calendarContainer.closest('[wire\\:id]') || calendarContainer.closest('[wire:id]');
                            if (wireElement) {
                                const wireId = wireElement.getAttribute('wire:id');
                                const livewireComponent = Livewire.find(wireId);
                                
                                if (livewireComponent) {
                                    livewireComponent.set('filters.date_range', dateStr);
                                    livewireComponent.set('filters.start_date', startDate);
                                    livewireComponent.set('filters.end_date', endDate);
                                    
                                    // Trigger data reload
                                    setTimeout(() => {
                                        livewireComponent.loadPreviewData();
                                    }, 100);
                                }
                            }
                        } else if (selectedDatesArg.length === 1) {
                            if (dateDisplay) {
                                const dateFormatted = selectedDatesArg[0].toLocaleDateString('id-ID', { 
                                    day: 'numeric', 
                                    month: 'short', 
                                    year: 'numeric' 
                                });
                                dateDisplay.innerHTML = `
                                    <span style="color: var(--aj-report-muted);">
                                        Memilih tanggal awal: ${dateFormatted}... (klik tanggal kedua)
                                    </span>
                                `;
                            }
                        } else {
                            if (dateDisplay) {
                                dateDisplay.innerHTML = '';
                            }
                            
                            // Clear in Livewire
                            const wireElement = calendarContainer.closest('[wire\\:id]') || calendarContainer.closest('[wire:id]');
                            if (wireElement) {
                                const wireId = wireElement.getAttribute('wire:id');
                                const livewireComponent = Livewire.find(wireId);
                                
                                if (livewireComponent) {
                                    livewireComponent.set('filters.date_range', null);
                                    livewireComponent.set('filters.start_date', null);
                                    livewireComponent.set('filters.end_date', null);
                                    
                                    // Trigger data reload
                                    setTimeout(() => {
                                        livewireComponent.loadPreviewData();
                                    }, 100);
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Failed to initialize Flatpickr:', error);
            }
        }
    </script>
</x-filament-panels::page>

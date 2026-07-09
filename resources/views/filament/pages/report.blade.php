<x-filament-panels::page>
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
            --aj-report-sidebar-width: 340px;
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

        .aj-report-layout {
            display: grid;
            grid-template-columns: var(--aj-report-sidebar-width) 1fr;
            gap: 24px;
            animation: fadeInUp 0.4s ease-out;
            position: relative;
            transition:
                grid-template-columns 0.38s cubic-bezier(0.4, 0, 0.2, 1),
                gap 0.38s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .aj-report-layout.aj-sidebar-closed {
            grid-template-columns: 0fr 1fr;
            gap: 0;
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
        
        /* Sidebar */
        .aj-report-sidebar {
            position: sticky;
            top: 24px;
            height: fit-content;
            min-width: 0;
            overflow-x: hidden;
            overflow-y: auto;
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
            transition:
                opacity 0.32s ease,
                transform 0.38s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.38s;
        }

        .aj-report-layout.aj-sidebar-closed .aj-report-sidebar {
            opacity: 0;
            visibility: hidden;
            transform: translateX(-16px);
            pointer-events: none;
            overflow: hidden;
        }

        .aj-report-sidebar .aj-sidebar-card {
            width: var(--aj-report-sidebar-width);
            min-width: var(--aj-report-sidebar-width);
        }

        .aj-sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--aj-report-divider);
        }

        .aj-sidebar-header-main {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .aj-sidebar-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border: 1px solid var(--aj-report-card-border);
            border-radius: 8px;
            background: var(--aj-report-soft-bg);
            color: var(--aj-report-muted);
            cursor: pointer;
            transition:
                color 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.15s ease;
        }

        .aj-sidebar-toggle-btn:hover {
            color: var(--aj-report-primary);
            border-color: var(--aj-report-primary);
            background: var(--aj-report-primary-light);
        }

        .aj-sidebar-toggle-btn:active {
            transform: scale(0.92);
        }

        .aj-sidebar-toggle-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .aj-sidebar-toggle-btn.is-closed svg {
            transform: rotate(180deg);
        }

        .aj-sidebar-reopen-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border: 1px solid var(--aj-report-card-border);
            border-radius: 8px;
            background: var(--aj-report-card-bg);
            color: var(--aj-report-text);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: var(--aj-report-shadow);
            transition:
                color 0.2s ease,
                border-color 0.2s ease,
                background 0.2s ease,
                transform 0.15s ease,
                box-shadow 0.2s ease;
        }

        .aj-sidebar-reopen-btn:hover {
            color: var(--aj-report-primary);
            border-color: var(--aj-report-primary);
            background: var(--aj-report-primary-light);
        }

        .aj-sidebar-reopen-btn:active {
            transform: scale(0.97);
        }

        .aj-sidebar-reopen-enter {
            transition: opacity 0.28s ease, transform 0.32s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .aj-sidebar-reopen-enter-start {
            opacity: 0;
            transform: translateX(-10px);
        }

        .aj-sidebar-reopen-enter-end {
            opacity: 1;
            transform: translateX(0);
        }

        .aj-sidebar-reopen-leave {
            transition: opacity 0.22s ease, transform 0.22s ease;
        }

        .aj-sidebar-reopen-leave-start {
            opacity: 1;
            transform: translateX(0);
        }

        .aj-sidebar-reopen-leave-end {
            opacity: 0;
            transform: translateX(-10px);
        }

        .aj-sidebar-reopen-btn svg {
            width: 16px;
            height: 16px;
        }

        [x-cloak] {
            display: none !important;
        }

        .aj-sidebar-icon {
            width: 40px;
            height: 40px;
            background: var(--aj-report-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--aj-report-primary);
        }

        .aj-sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--aj-report-text);
            margin: 0;
        }

        .aj-sidebar-card {
            background: var(--aj-report-card-bg);
            border-radius: 12px;
            box-shadow: var(--aj-report-shadow);
            border: 1px solid var(--aj-report-card-border);
            padding: 24px;
            transition: all 0.2s ease;
        }

        .aj-sidebar-card:hover {
            box-shadow: var(--aj-report-shadow-lg);
        }

        .aj-sidebar-section {
            margin-bottom: 24px;
        }

        .aj-sidebar-section:last-child {
            margin-bottom: 0;
        }

        .aj-sidebar-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--aj-report-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .aj-sidebar-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid var(--aj-report-divider);
        }

        /* Main Content */
        .aj-report-main {
            min-width: 0;
            transition: transform 0.38s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .aj-main-card {
            /* border: 1px solid red; */
            /* height: 970px; */
            background: var(--aj-report-card-bg);
            border-radius: 12px;
            box-shadow: var(--aj-report-shadow);
            border: 1px solid var(--aj-report-card-border);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .aj-main-card:hover {
            box-shadow: var(--aj-report-shadow-lg);
        }

        .aj-main-header {
            padding: 24px;
            background: var(--aj-report-gradient);
            border-bottom: 1px solid var(--aj-report-card-border);
        }

        .aj-main-title-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .aj-badges-container {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .aj-main-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--aj-report-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .aj-main-title-icon {
            color: var(--aj-report-primary);
        }

        .aj-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: var(--aj-report-primary-light);
            color: var(--aj-report-primary-dark);
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .aj-period-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: var(--aj-report-soft-bg);
            color: var(--aj-report-muted);
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Search Input */
        .aj-search-wrapper {
            position: relative;
        }

        .aj-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--aj-report-muted);
            pointer-events: none;
        }

        .aj-search-input {
            width: 100%;
            padding: 12px 100px 12px 44px;
            border: 2px solid var(--aj-report-card-border);
            border-radius: 10px;
            font-size: 14px;
            background: var(--aj-report-surface);
            color: var(--aj-report-text);
            transition: all 0.2s ease;
        }

        .aj-search-input:focus {
            outline: none;
            border-color: var(--aj-report-primary);
            box-shadow: 0 0 0 3px var(--aj-report-primary-light);
        }

        .aj-search-input::placeholder {
            color: var(--aj-report-muted);
        }

        .aj-search-clear {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            padding: 8px 16px;
            background: #fee2e2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #dc2626;
            transition: all 0.2s ease;
        }

        .aj-search-clear:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* Table */
        .aj-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .aj-table {
            width: 100%;
            border-collapse: collapse;
        }

        .aj-table thead {
            background: var(--aj-report-soft-bg);
            border-bottom: 2px solid var(--aj-report-card-border);
        }

        .aj-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--aj-report-muted);
            white-space: nowrap;
        }

        .aj-table tbody tr {
            border-bottom: 1px solid var(--aj-report-divider);
            transition: all 0.15s ease;
        }

        .aj-table tbody tr:hover {
            background: var(--aj-report-soft-bg);
        }

        .aj-table tbody tr:last-child {
            border-bottom: none;
        }

        .aj-table td {
            padding: 16px 20px;
            font-size: 14px;
            color: var(--aj-report-text);
        }

        .aj-number-cell {
            font-weight: 600;
            color: var(--aj-report-text);
            font-family: 'Courier New', monospace;
        }

        .aj-amount-cell {
            text-align: right;
            font-weight: 700;
            color: var(--aj-report-primary);
            font-family: 'Courier New', monospace;
            font-size: 15px;
        }

        /* Status Badges */
        .aj-status-badge {
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

        .aj-status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .aj-status-gray {
            background: #f3f4f6;
            color: #374151;
        }

        .dark .aj-status-gray,
        [data-theme="dark"] .aj-status-gray {
            background: #374151;
            color: #d1d5db;
        }

        .aj-status-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .dark .aj-status-blue,
        [data-theme="dark"] .aj-status-blue {
            background: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
        }

        .aj-status-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        .dark .aj-status-yellow,
        [data-theme="dark"] .aj-status-yellow {
            background: rgba(146, 64, 14, 0.3);
            color: #fcd34d;
        }

        .aj-status-green {
            background: #d1fae5;
            color: #166534;
        }

        .dark .aj-status-green,
        [data-theme="dark"] .aj-status-green {
            background: rgba(22, 101, 52, 0.3);
            color: #86efac;
        }

        .aj-status-purple {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .dark .aj-status-purple,
        [data-theme="dark"] .aj-status-purple {
            background: rgba(107, 33, 168, 0.3);
            color: #d8b4fe;
        }

        /* Action Button */
        .aj-btn-view {
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

        .aj-btn-view:hover {
            background: var(--aj-report-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        }

        .aj-btn-view svg {
            width: 16px;
            height: 16px;
        }

        /* Pagination */
        .aj-pagination {
            padding: 20px 24px;
            border-top: 2px solid var(--aj-report-divider);
            background: var(--aj-report-soft-bg);
        }

        .aj-pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .aj-pagination-info {
            font-size: 14px;
            color: var(--aj-report-muted);
            font-weight: 500;
        }

        .aj-pagination-info strong {
            color: var(--aj-report-text);
        }

        .aj-pagination-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* Empty State */
        .aj-empty-state {
            padding: 64px 24px;
            text-align: center;
        }

        .aj-empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--aj-report-soft-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .aj-empty-state-icon svg {
            width: 40px;
            height: 40px;
            color: var(--aj-report-muted);
        }

        .aj-empty-state-text {
            font-size: 18px;
            font-weight: 600;
            color: var(--aj-report-text);
            margin: 0 0 8px;
        }

        .aj-empty-state-hint {
            font-size: 14px;
            color: var(--aj-report-muted);
        }

        /* Utility */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .aj-export-dialog-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.32);
            backdrop-filter: blur(4px) saturate(110%);
            -webkit-backdrop-filter: blur(4px) saturate(110%);
            z-index: 40;
        }

        .aj-export-dialog {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .aj-export-dialog-card {
            width: min(720px, 100%);
            max-height: 80vh;
            overflow: hidden;
            background: var(--aj-report-card-bg);
            border: 1px solid var(--aj-report-card-border);
            border-radius: 14px;
            box-shadow: var(--aj-report-shadow-lg);
            display: flex;
            flex-direction: column;
        }

        .aj-export-dialog-header {
            padding: 18px 20px 14px;
            border-bottom: 1px solid var(--aj-report-card-border);
            background: var(--aj-report-card-bg);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .aj-export-dialog-body {
            padding: 14px 20px;
            overflow-y: auto;
            max-height: calc(80vh - 150px);
        }

        .aj-export-dialog-footer {
            padding: 14px 20px 18px;
            border-top: 1px solid var(--aj-report-card-border);
            background: var(--aj-report-card-bg);
            position: sticky;
            bottom: 0;
            z-index: 2;
        }

        .aj-export-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .aj-export-item {
            border: 1px solid var(--aj-report-card-border);
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .aj-export-toggle {
            width: 44px;
            height: 24px;
            border-radius: 999px;
            background: #cbd5e1;
            position: relative;
            transition: background .2s ease;
        }

        .aj-export-toggle::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #fff;
            transition: transform .2s ease;
        }

        input:checked + .aj-export-toggle {
            background: var(--aj-report-primary);
        }

        input:checked + .aj-export-toggle::after {
            transform: translateX(20px);
        }

        @media (prefers-reduced-motion: reduce) {
            .aj-report-layout,
            .aj-report-sidebar,
            .aj-report-main,
            .aj-sidebar-toggle-btn,
            .aj-sidebar-toggle-btn svg,
            .aj-sidebar-reopen-btn,
            .aj-sidebar-reopen-enter,
            .aj-sidebar-reopen-leave {
                transition: none !important;
                animation: none !important;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .aj-report-layout {
                grid-template-columns: 1fr !important;
            }

            .aj-report-layout.aj-sidebar-closed {
                gap: 0;
            }

            .aj-report-sidebar {
                position: static;
                max-height: 3000px;
                overflow: hidden;
                transition:
                    max-height 0.38s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.32s ease,
                    transform 0.38s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0.38s,
                    margin 0.38s ease;
            }

            .aj-report-layout.aj-sidebar-closed .aj-report-sidebar {
                max-height: 0;
                transform: translateY(-10px);
                margin-bottom: 0;
            }

            .aj-report-sidebar .aj-sidebar-card {
                width: 100%;
                min-width: 0;
            }

            .aj-sidebar-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .aj-report-layout {
                gap: 16px;
            }

            .aj-sidebar-card,
            .aj-main-card {
                border-radius: 10px;
            }

            .aj-main-header {
                padding: 20px;
            }

            .aj-main-title {
                font-size: 20px;
            }

            .aj-main-title-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .aj-badges-container {
                width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
            }

            .aj-count-badge,
            .aj-period-badge {
                font-size: 12px;
                padding: 5px 12px;
                flex-shrink: 0;
            }

            .aj-period-badge {
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .aj-table th,
            .aj-table td {
                padding: 12px 16px;
                font-size: 13px;
            }

            .aj-pagination-wrapper {
                flex-direction: column;
                gap: 16px;
            }

            .aj-empty-state {
                padding: 48px 16px;
            }
        }
    </style>

    <div
        class="aj-report-layout"
        x-data="{ sidebarOpen: true }"
        :class="{ 'aj-sidebar-closed': !sidebarOpen }"
    >
        {{-- Left Sidebar --}}
        <aside class="aj-report-sidebar" :aria-hidden="sidebarOpen ? 'false' : 'true'">
            <div class="aj-sidebar-card">
                <div class="aj-sidebar-header">
                    <div class="aj-sidebar-header-main">
                        <div class="aj-sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                        </div>
                        <h2 class="aj-sidebar-title">Filter Laporan</h2>
                    </div>
                    <button
                        type="button"
                        class="aj-sidebar-toggle-btn"
                        :class="{ 'is-closed': !sidebarOpen }"
                        @click="sidebarOpen = false"
                        title="Tutup filter"
                        aria-label="Tutup filter sidebar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 18l-6-6 6-6"></path>
                        </svg>
                    </button>
                </div>

                {{-- Filter Form --}}
                <div class="aj-sidebar-section">
                    {{ $this->filterForm }}
                </div>

                {{-- Calendar --}}
                <div class="aj-sidebar-section">
                    <label class="aj-sidebar-label">Rentang Tanggal</label>
                    <x-date-range-picker
                        :startDate="$calendarStartDate"
                        :endDate="$calendarEndDate"
                        :currentMonth="$calendarCurrentMonth"
                        :currentYear="$calendarCurrentYear"
                    />
                </div>

                {{-- Actions --}}
                <div class="aj-sidebar-actions">
                    <x-filament::button color="gray" wire:click="resetFilters" full-width>
                        <x-filament::icon icon="heroicon-m-arrow-path" class="w-4 h-4 mr-1" />
                        Reset Filter
                    </x-filament::button>
                    
                    <x-filament::button 
                        color="success" 
                        wire:click="openExportDialog"
                        :disabled="$resultCount === 0"
                        full-width>
                        <x-filament::icon icon="heroicon-m-arrow-down-tray" class="w-4 h-4 mr-1" />
                        Download Excel
                    </x-filament::button>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="aj-report-main">
            <div class="aj-main-card">
                <div class="aj-main-header">
                    <div class="aj-main-title-section">
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <button
                                type="button"
                                class="aj-sidebar-reopen-btn"
                                x-show="!sidebarOpen"
                                x-cloak
                                x-transition:enter="aj-sidebar-reopen-enter"
                                x-transition:enter-start="aj-sidebar-reopen-enter-start"
                                x-transition:enter-end="aj-sidebar-reopen-enter-end"
                                x-transition:leave="aj-sidebar-reopen-leave"
                                x-transition:leave-start="aj-sidebar-reopen-leave-start"
                                x-transition:leave-end="aj-sidebar-reopen-leave-end"
                                @click="sidebarOpen = true"
                                title="Buka filter"
                                aria-label="Buka filter sidebar"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                Filter Laporan
                            </button>
                            <h2 class="aj-main-title">
                            <svg class="aj-main-title-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                        
                        <div class="aj-badges-container">
                            <span class="aj-count-badge">
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
                                <span class="aj-period-badge">
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
                        </div>
                    </div>
                    
                    {{-- Search --}}
                    <div class="aj-search-wrapper">
                        <svg class="aj-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text"
                               wire:model.live.debounce.300ms="searchQuery"
                               placeholder="Search"
                               class="aj-search-input">
                        
                        @if(!empty($searchQuery))
                        <button wire:click="$set('searchQuery', ''); $wire.loadPreviewData()"
                                class="aj-search-clear">
                            X
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Table --}}
                @if(count($previewRows) > 0)
                <div class="aj-table-container">
                    <table class="aj-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                
                                @if($filters['report_type'] === 'jasa')
                                <!-- <th>No Jasa</th> -->
                                <th>No Ref</th>
                                <th>Pelanggan</th>
                                <th>Petugas</th>
                                @else
                                <!-- <th>No Produksi</th> -->
                                <th>No Ref</th>
                                <th>Branch</th>
                                <th>Team</th>
                                @endif
                                
                                <th class="text-right">Total</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previewRows as $index => $row)
                            <tr>
                                <td class="whitespace-nowrap text-center">
                                    {{ (($currentPage - 1) * $perPage) + $index + 1 }}.
                                </td>
                                
                                @if($filters['report_type'] === 'jasa')
                                <!-- <td class="whitespace-nowrap aj-number-cell">
                                    {{ $row['number'] }}
                                </td> -->
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
                                <!-- <td class="whitespace-nowrap aj-number-cell">
                                    {{ $row['number'] }}
                                </td> -->
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
                                
                                
                                <td class="whitespace-nowrap aj-amount-cell">
                                    Rp {{ number_format($row['total_harga'], 0, ',', '.') }}
                                </td>
                                
                                <td class="whitespace-nowrap">
                                    @php
                                        $createdAt = $row['created_at'] ?? null;

                                        if (blank($createdAt) || $createdAt === '-') {
                                            $formattedCreatedAt = '-';
                                        } else {
                                            try {
                                                $formattedCreatedAt = \Carbon\Carbon::parse($createdAt)->format('d/m/Y');
                                            } catch (\Exception $e) {
                                                $formattedCreatedAt = $createdAt;
                                            }
                                        }
                                    @endphp
                                    {{ $formattedCreatedAt }}
                                </td>
                                
                                <td class="whitespace-nowrap text-center">
                                    <a href="/admin/report/preview-invoice?number={{ urlencode($row['number']) }}&type={{ urlencode($filters['report_type']) }}"
                                       target="_blank"
                                       class="aj-btn-view">
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
                
                <div class="aj-pagination">
                    <div class="aj-pagination-wrapper">
                        <div class="aj-pagination-info">
                            Menampilkan <strong>{{ (($currentPage - 1) * $perPage) + 1 }}</strong> - <strong>{{ min($currentPage * $perPage, $resultCount) }}</strong> dari <strong>{{ $resultCount }}</strong> data
                        </div>
                        
                        <div class="aj-pagination-buttons">
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
                @else
                <div class="aj-empty-state">
                    <div class="aj-empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="aj-empty-state-text">Tidak ada data untuk ditampilkan</p>
                    <p class="aj-empty-state-hint">Coba ubah filter atau rentang tanggal</p>
                </div>
                @endif
            </div>
        </main>
    </div>

    @if($showExportDialog)
        <div class="aj-export-dialog-overlay" wire:click="closeExportDialog"></div>
        <div class="aj-export-dialog">
            <div class="aj-export-dialog-card">
                <div class="aj-export-dialog-header">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--aj-report-text); margin: 0;">Pilih Kolom Export Excel</h3>
                            <p style="font-size: 13px; color: var(--aj-report-muted); margin: 4px 0 0;">
                                Aktifkan kolom yang ingin ditampilkan pada file {{ $filters['report_type'] === 'produksi' ? 'Produksi' : 'Jasa' }}.
                            </p>
                        </div>
                        <x-filament::button color="gray" size="sm" wire:click="closeExportDialog" aria-label="Tutup dialog">
                            <x-filament::icon icon="heroicon-m-x-mark" class="w-5 h-5" />
                        </x-filament::button>
                    </div>
                </div>

                <div class="aj-export-dialog-body">
                    <div class="aj-export-grid">
                        @foreach($this->activeExportColumnOptions as $key => $label)
                            <label class="aj-export-item">
                                <span style="font-size: 14px; font-weight: 600; color: var(--aj-report-text);">{{ $label }}</span>
                                <span style="position: relative; display: inline-flex;">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectedExportColumns.{{ $key }}"
                                        style="position: absolute; opacity: 0; pointer-events: none;"
                                    />
                                    <span class="aj-export-toggle"></span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="aj-export-dialog-footer">
                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <x-filament::button color="gray" wire:click="closeExportDialog">
                            Batal
                        </x-filament::button>
                        <x-filament::button color="success" wire:click="confirmExportExcel">
                            Export Sekarang
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>

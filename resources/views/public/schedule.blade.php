<div class="public-schedule-page">
    {{-- Set Page Title --}}
    <script>
        document.title = 'Jadwal Produksi & Jasa - Artha Jaya';
    </script>

    <script>
        const viewportMeta = document.createElement('meta');
        viewportMeta.name = 'viewport';
        viewportMeta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
        document.head.appendChild(viewportMeta);
    </script>

    
    <div class="schedule-container">
        {{-- Sidebar Section --}}
        <div class="schedule-sidebar">
            {{-- Mini Calendar --}}
            <div class="mini-calendar">
                <div class="mini-calendar-header">
                    <h3>{{ $monthName }}</h3>
                    <div class="mini-calendar-nav">
                        <a href="{{ route('public.schedule', ['month' => $prevMonth]) }}" class="nav-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <a href="{{ route('public.schedule', ['month' => $nextMonth]) }}" class="nav-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="mini-calendar-day-names">
                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div>{{ $day }}</div>
                    @endforeach
                </div>
                
                <div class="mini-calendar-days">
                    @foreach ($calendarDays as $day)
                        @if ($day === null)
                            <div class="mini-day-empty"></div>
                        @else
                            <a href="{{ route('public.schedule', ['date' => $day['date']]) }}" 
                               class="mini-day-btn {{ $day['isSelected'] ? 'selected' : '' }} {{ $day['isToday'] ? 'today' : '' }}"
                               data-date="{{ $day['date'] }}"
                               data-has-jasa="{{ $day['hasJasa'] ? '1' : '0' }}"
                               data-has-produksi="{{ $day['hasProduksi'] ? '1' : '0' }}">
                                {{ $day['day'] }}
                                @if ($day['hasJasa'] || $day['hasProduksi'])
                                    <div class="mini-day-dots">
                                        @if ($day['hasJasa'])<span class="dot jasa"></span>@endif
                                        @if ($day['hasProduksi'])<span class="dot produksi"></span>@endif
                                    </div>
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            
            {{-- Filters Section --}}
            <div class="sidebar-filters">
                <div class="filter-section">
                    <div class="filter-section-header">
                        <h4 class="filter-title">Jenis</h4>
                        <button class="filter-toggle-btn" data-target="jenis-filter">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <div class="filter-options" id="jenis-filter">
                        <label class="filter-checkbox">
                            <input type="checkbox" id="filter-produksi" checked value="produksi">
                            <span class="checkmark produksi"></span>
                            <span>Produksi</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" id="filter-jasa" checked value="jasa">
                            <span class="checkmark jasa"></span>
                            <span>Jasa</span>
                        </label>
                    </div>
                </div>
                
                <div class="filter-section">
                    <div class="filter-section-header">
                        <h4 class="filter-title">Status Produksi</h4>
                        <button class="filter-toggle-btn" data-target="status-produksi-filter">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <div class="filter-options" id="status-produksi-filter">
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="baru">
                            <span class="checkmark"></span>
                            <span>Produksi Baru</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="proses">
                            <span class="checkmark"></span>
                            <span>Proses</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="siap diambil">
                            <span class="checkmark"></span>
                            <span>Siap Diambil</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="selesai">
                            <span class="checkmark"></span>
                            <span>Selesai</span>
                        </label>
                    </div>
                </div>
                
                <div class="filter-section">
                    <div class="filter-section-header">
                        <h4 class="filter-title">Status Jasa</h4>
                        <button class="filter-toggle-btn" data-target="status-jasa-filter">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <div class="filter-options" id="status-jasa-filter">
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="jasa baru">
                            <span class="checkmark"></span>
                            <span>Jasa Baru</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="terjadwal">
                            <span class="checkmark"></span>
                            <span>Terjadwal</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="selesai dikerjakan">
                            <span class="checkmark"></span>
                            <span>Selesai Dikerjakan</span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="status-filter" checked value="selesai">
                            <span class="checkmark"></span>
                            <span>Selesai</span>
                        </label>
                    </div>
                </div>
            </div>
            
            {{-- Summary Stats --}}
            <div class="sidebar-stats">
                <h4 class="stats-title">Keterangan</h4>
                <div class="stat-container">
                    <div class="stat-row">
                        <div class="stat-dot jasa"></div>
                        <span>Jasa</span>
                    </div>
                    <div class="stat-row">
                        <div class="stat-dot selesai"></div>
                        <span>Selesai</span>
                    </div>
                </div>
                <div class="stat-container">
                    <div class="stat-row">
                        <div class="stat-dot produksi"></div>
                        <span>Produksi</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Main Calendar Section --}}
        <div class="schedule-main">
            <div class="main-header">
                <div class="main-header-left">
                    <h1>{{ $monthName }}</h1>
                </div>

                <div class="main-header-actions">
                    <button type="button" class="btn-fullscreen" id="toggleFullscreen">
                        <span class="fullscreen-enter">Fullscreen</span>
                        <span class="fullscreen-exit">Close Fullscreen</span>
                    </button>

                    <a href="{{ route('public.schedule', ['date' => now()->toDateString()]) }}" class="btn-today">
                        Today
                    </a>
                </div>
            </div>
            
            <div class="main-calendar">
                {{-- Day Names Header --}}
                <div class="main-day-names">
                    @foreach (['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $day)
                        <div class="day-name">{{ $day }}</div>
                    @endforeach
                </div>
                
                {{-- Calendar Grid --}}
                <div class="main-calendar-grid">
                    @foreach ($calendarDays as $day)
                        @if ($day === null)
                            <div class="main-day-empty"></div>
                        @else
                            <div class="main-day-cell 
                                {{ $day['isSelected'] ? 'selected' : '' }} 
                                {{ $day['isToday'] ? 'today' : '' }}"
                                data-date="{{ $day['date'] }}">
                                <div class="day-header">
                                    <span class="day-number">{{ $day['day'] }}</span>
                                    @if (isset($eventsByDate[$day['date']]))
                                        @php
                                            $dayEvents = $eventsByDate[$day['date']];
                                            $hiddenCount = count($dayEvents) - 2;
                                        @endphp
                                        @if ($hiddenCount > 0)
                                            <span class="event-more-badge" data-date="{{ $day['date'] }}">+{{ $hiddenCount }} More</span>
                                        @endif
                                    @endif
                                </div>
                                
                                {{-- Display events for this day --}}
                                @if (isset($eventsByDate[$day['date']]))
                                    @php
                                        $dayEvents = $eventsByDate[$day['date']];
                                        $visibleEvents = array_slice($dayEvents, 0, 2);
                                        $hiddenCount = count($dayEvents) - 2;
                                    @endphp
                                    
                                    @foreach ($visibleEvents as $event)
                                        @php
                                            $statusClass = str_replace(' ', '-', $event['status'] ?? '');
                                        @endphp
                                        <div class="event-item {{ $event['type'] }} {{ $statusClass }}"
                                             data-type="{{ $event['type'] }}" 
                                             data-status="{{ $event['status'] ?? 'baru' }}">
                                            <div class="event-content">
                                                <div class="event-header">
                                                    <span class="event-time">{{ $event['title'] }}</span>
                                                    <span class="event-type-badge {{ $event['type'] }}">
                                                        {{ $event['type'] === 'jasa' ? 'Jasa' : 'Produksi' }}
                                                    </span>
                                                </div>
                                                @if ($event['location'])
                                                    <div class="event-location">
                                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span>{{ Str::limit($event['location'], 25) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    {{-- Events Modal --}}
    <div class="events-modal" id="eventsModal">
        <div class="modal-overlay" id="modalOverlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><span id="modalDate"></span></h3>
                <button class="modal-close" id="modalClose">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                {{-- Events will be loaded here --}}
            </div>
        </div>
    </div>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Google Sans', Roboto, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            color: #3c4043;
            zoom: 100%;
            font-size: 16px;
        }

        ::-webkit-scrollbar {
            display: none;
        }
        
        .public-schedule-page {
            height: 100%;
            background: #ffffff;
        }
        
        .schedule-container {
            display: flex;
            height: 100%;
            overflow: hidden;
        }
        
        /* Sidebar Styles */
        .schedule-sidebar {
            width: 280px;
            border-right: 1px solid #dadce0;
            padding: 16px;
            background: #ffffff;
            overflow-y: auto;
        }
        
        .mini-calendar {
            margin-bottom: 24px;
        }
        
        .mini-calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        
        .mini-calendar-header h3 {
            font-size: 14px;
            font-weight: 500;
            color: #3c4043;
        }
        
        .mini-calendar-nav {
            display: flex;
            gap: 4px;
        }
        
        .nav-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #5f6368;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .nav-btn:hover {
            background: #f1f3f4;
        }
        
        .mini-calendar-day-names {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            margin-bottom: 8px;
        }
        
        .mini-calendar-day-names > div {
            text-align: center;
            font-size: 10px;
            font-weight: 500;
            color: #70757a;
            padding: 4px 0;
        }
        
        .mini-calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }
        
        .mini-day-empty {
            height: 32px;
        }
        
        .mini-day-btn {
            height: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #3c4043;
            font-size: 12px;
            /* border-radius: 50%; */
            position: relative;
            transition: background 0.2s;
        }
        
        .mini-day-btn:hover {
            background: #f1f3f4;
        }
        
        .mini-day-btn.today {
            color: #1a73e8;
            font-weight: 600;
        }
        
        .mini-day-btn.selected {
            background: #1a73e8;
            color: #ffffff;
        }
        
        .mini-day-btn.selected:hover {
            background: #1557b0;
        }
        
        .mini-day-dots {
            display: flex;
            gap: 2px;
            position: absolute;
            bottom: 2px;
        }
        
        .mini-day-dots .dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
        }
        
        .mini-day-dots .dot.jasa {
            background: #f59e0b;
        }
        
        .mini-day-dots .dot.produksi {
            background: #3b82f6;
        }
        
        /* Sidebar Stats */
        .sidebar-stats {
            padding-top: 16px;
        }

        .stat-container {
            display: flex;
            /* border: 1px solid red; */
            justify-content: space-between;
        }
        
        .stat-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
            font-size: 13px;
            color: #3c4043;
        }
        
        .stat-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .stat-dot.jasa {
            background: #f59e0b;
        }
        
        .stat-dot.produksi {
            background: #3b82f6;
        }
        
        .stat-dot.selesai {
            background: #10b981;
        }
        
        /* Filters Styles */
        .sidebar-filters {
            margin: 16px 0 7px 0;
            padding: 16px 0;
            border-top: 1px solid #dadce0;
            border-bottom: 1px solid #dadce0;
        }
        
        .filter-section {
            margin-bottom: 16px;
        }
        
        .filter-section:last-child {
            margin-bottom: 0;
        }
        
        .filter-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .filter-toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #5f6368;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        
        .filter-toggle-btn:hover {
            color: #3c4043;
            background: rgba(0, 0, 0, 0.04);
        }
        
        .filter-toggle-btn svg {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .filter-toggle-btn.collapsed svg {
            transform: rotate(-90deg);
        }
        
        .filter-toggle-btn:active {
            transform: scale(0.95);
        }
        
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 6px;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                        opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 500px;
            opacity: 1;
            transform: translateY(0);
        }
        
        .filter-options.collapsed {
            max-height: 0;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-10px);
        }
        
        .filter-options.disabled {
            opacity: 0.4;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .filter-title {
            font-size: 11px;
            font-weight: 600;
            color: #5f6368;
            text-transform: uppercase;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }
        
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: #3c4043;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .filter-checkbox:hover {
            background: #f1f3f4;
        }
        
        .filter-checkbox input[type="checkbox"] {
            display: none;
        }
        
        .checkmark {
            width: 18px;
            height: 18px;
            border: 2px solid #dadce0;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        
        .checkmark.produksi {
            border-color: #3b82f6;
        }
        
        .checkmark.jasa {
            border-color: #3b82f6;
        }
        
        .filter-checkbox input[type="checkbox"]:checked + .checkmark {
            background: #1a73e8;
            border-color: #1a73e8;
        }
        
        .filter-checkbox input[type="checkbox"]:checked + .checkmark.produksi {
            background: #3b82f6;
            border-color: #3b82f6;
        }
        
        .filter-checkbox input[type="checkbox"]:checked + .checkmark.jasa {
            background: #3b82f6;
            border-color: #3b82f6;
        }
        
        .checkmark::after {
            content: '';
            display: none;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            margin-top: -2px;
        }
        
        .filter-checkbox input[type="checkbox"]:checked + .checkmark::after {
            display: block;
        }
        
        .stats-title {
            font-size: 11px;
            font-weight: 600;
            color: #5f6368;
            text-transform: uppercase;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }
        
        /* Main Calendar Styles */
        .schedule-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .main-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid #dadce0;
        }
        
        .main-header h1 {
            font-size: 22px;
            font-weight: 400;
            color: #3c4043;
        }
        
        .btn-today {
            background: #1a73e8;
            color: #ffffff;
            border-color: #1a73e8;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .main-calendar {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .main-day-names {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            border-bottom: 1px solid #dadce0;
        }
        
        .main-day-names .day-name {
            padding: 12px 8px;
            text-align: center;
            font-size: 11px;
            font-weight: 500;
            color: #70757a;
            text-transform: uppercase;
        }
        
        .main-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-template-rows: repeat(auto-fill, minmax(125px, 1fr));
            flex: 1;
            overflow-y: auto;
        }
        
        .main-day-empty {
            min-height: 125px;
            border-bottom: 1px solid #dadce0;
            border-right: 1px solid #dadce0;
            background: #f8f9fa;
        }
        
        .main-day-cell {
            min-height: 125px;
            border-bottom: 1px solid #dadce0;
            border-right: 1px solid #dadce0;
            padding: 8px;
            background: #ffffff;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .main-day-cell:nth-child(7n) {
            border-right: none;
        }
        
        .main-day-cell:hover {
            background: #f8f9fa;
        }
        
        .main-day-cell.today {
            /* background: #e8f0fe; */
        }
        
        .main-day-cell.selected {
            /* background: #e8f0fe; */
            box-shadow: inset 0 0 0 2px #1a73e8;
        }
        
        .day-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .event-more-badge {
            background: #1a73e8;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: badgePulse 2s ease-in-out infinite;
        }
        
        @keyframes badgePulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.20);
            }
        }
        
        .event-more-badge:hover {
            background: #1557b0;
            transform: scale(1.15);
            animation: none;
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.4);
        }
        
        .day-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            font-size: 12px;
            color: #3c4043;
        }
        
        .main-day-cell.today .day-number {
            background: #1a73e8;
            color: #ffffff;
            border-radius: 50%;
            font-weight: 600;
        }
        
        /* Event Items */
        .event-item {
            margin-bottom: 4px;
            border-radius: 4px;
            border-left: 3px solid;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .event-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .event-item.jasa {
            border-left-color: #f59e0b;
        }
        
        .event-item.produksi {
            border-left-color: #3b82f6;
        }
        
        .event-item.jasa.selesai,
        .event-item.produksi.selesai {
            border-left-color: #10b981 !important;
        }
        
        .event-content {
            padding: 4px 6px;
        }
        
        .event-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 4px;
            margin-bottom: 2px;
        }
        
        .event-time {
            font-size: 10px;
            font-weight: 500;
            color: #5f6368;
            flex-shrink: 0;
        }
        
        .event-type-badge {
            font-size: 8px;
            font-weight: 600;
            padding: 1px 4px;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .event-type-badge.jasa {
            background: #fef3c7;
            color: #92400e;
        }
        
        .event-type-badge.produksi {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .event-title {
            font-size: 11px;
            font-weight: 500;
            color: #3c4043;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 2px;
        }
        
        .event-location {
            display: flex;
            align-items: center;
            gap: 2px;
            font-size: 9px;
            color: #70757a;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .event-location svg {
            flex-shrink: 0;
            opacity: 0.7;
        }
        
        /* Events Modal */
        .events-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        
        .events-modal.active {
            display: flex;
        }
        
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal-content {
            position: relative;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            animation: modalSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid #dadce0;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #202124;
            margin: 0;
        }
        
        /* .modal-title span {
            color: #1a73e8;
        } */
        
        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            color: #5f6368;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-close:hover {
            background: #f1f3f4;
            color: #202124;
        }
        
        .modal-body {
            padding: 20px 24px;
            overflow-y: auto;
            flex: 1;
        }
        
        .modal-event-item {
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid;
            margin-bottom: 12px;
            background: #f8f9fa;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .modal-event-item.jasa {
            border-left-color: #f59e0b;
        }
        
        .modal-event-item.produksi {
            border-left-color: #3b82f6;
        }
        
        .modal-event-item.selesai {
            border-left-color: #10b981 !important;
        }
        
        .modal-event-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .modal-event-type {
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .modal-event-type.jasa {
            background: #fef3c7;
            color: #92400e;
        }
        
        .modal-event-type.produksi {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .modal-event-status {
            font-size: 11px;
            font-weight: 500;
            color: #5f6368;
            text-transform: capitalize;
        }
        
        .modal-event-title {
            font-size: 14px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 4px;
        }
        
        .modal-event-location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #5f6368;
        }
        
        .modal-event-location svg {
            flex-shrink: 0;
        }
        
        /* Responsive */
        /* Large Desktop */
        @media (min-width: 1400px) {
            .schedule-sidebar {
                width: 320px;
            }
        }
        
        /* Desktop */
        @media (max-width: 1200px) {
            .schedule-sidebar {
                width: 260px;
            }
            
            .main-header h1 {
                font-size: 20px;
            }
        }
        
        /* Tablet Landscape */
        @media (max-width: 1024px) {
            .schedule-sidebar {
                width: 220px;
            }
            
            body {
                zoom: 100%;
            }
            
            .mini-calendar-header h3 {
                font-size: 13px;
            }
            
            .main-header {
                padding: 12px 20px;
            }
            
            .main-header h1 {
                font-size: 18px;
            }
            
            .main-day-cell {
                min-height: 110px;
            }
        }
        
        /* Tablet Portrait */
        @media (max-width: 768px) {
            body {
                zoom: 100%;
            }
            
            .schedule-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            /* Sidebar becomes horizontal scrollable section */
            .schedule-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 2px solid #dadce0;
                padding: 12px;
                background: #f8f9fa;
            }
            
            .mini-calendar {
                margin-bottom: 12px;
            }
            
            .mini-calendar-header {
                margin-bottom: 12px;
            }
            
            .mini-calendar-header h3 {
                font-size: 14px;
                font-weight: 600;
            }
            
            .nav-btn {
                width: 30px;
                height: 30px;
            }
            
            .mini-day-btn {
                height: 32px;
                font-size: 12px;
            }
            
            /* Filters in horizontal scroll */
            .sidebar-filters {
                margin: 8px 0;
                padding: 12px 0;
                border-top: 1px solid #e0e0e0;
                border-bottom: 1px solid #e0e0e0;
            }
            
            .filter-section {
                margin-bottom: 12px;
            }
            
            .filter-title {
                font-size: 11px;
            }
            
            .filter-checkbox {
                font-size: 13px;
                padding: 6px 8px;
            }
            
            .checkmark {
                width: 18px;
                height: 18px;
            }
            
            .sidebar-stats {
                padding-top: 8px;
            }
            
            .stat-row {
                font-size: 12px;
                padding: 4px 0;
            }
            
            .stats-title {
                font-size: 11px;
                margin-bottom: 6px;
            }
            
            /* Main calendar takes full height */
            .schedule-main {
                flex: 1;
                min-height: calc(100vh - 350px);
            }
            
            .main-header {
                padding: 12px 16px;
                background: #ffffff;
                position: sticky;
                top: 0;
                z-index: 10;
            }
            
            .main-header h1 {
                font-size: 18px;
                font-weight: 500;
            }
            
            .btn-today {
                padding: 6px 14px;
                font-size: 13px;
                background: #1a73e8;
                color: #ffffff;
                border-color: #1a73e8;
            }
            
            .btn-today:hover {
                background: #1557b0;
            }
            
            .main-day-names {
                position: sticky;
                top: 0;
                background: #ffffff;
                z-index: 5;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            
            .main-day-names .day-name {
                padding: 10px 4px;
                font-size: 10px;
            }
            
            .main-calendar-grid {
                grid-template-rows: repeat(auto-fill, minmax(100px, 1fr));
            }
            
            .main-day-cell {
                min-height: 100px;
                padding: 6px;
            }
            
            .day-number {
                width: 24px;
                height: 24px;
                font-size: 11px;
            }
            
            .event-more-badge {
                font-size: 9px;
                padding: 2px 5px;
            }
            
            .event-item {
                margin-bottom: 3px;
            }
            
            .event-content {
                padding: 4px 5px;
            }
            
            .event-time {
                font-size: 9px;
                font-weight: 600;
            }
            
            .event-type-badge {
                font-size: 7px;
                padding: 1px 3px;
            }
            
            .event-location {
                font-size: 8px;
            }
            
            .event-location svg {
                width: 8px;
                height: 8px;
            }
            
            /* Modal improvements */
            .modal-content {
                width: 95%;
                max-width: 500px;
                max-height: 85vh;
            }
            
            .modal-header {
                padding: 16px 20px;
                background: #f8f9fa;
            }
            
            .modal-title {
                font-size: 16px;
            }
            
            .modal-body {
                padding: 16px 20px;
            }
            
            .modal-event-item {
                padding: 12px;
                margin-bottom: 10px;
            }
        }
        
        /* Mobile Large */
        @media (max-width: 640px) {
            .public-schedule-page {
                padding-bottom: 20px;
            }
            
            .schedule-sidebar {
                padding: 10px;
            }
            
            .mini-calendar-day-names > div {
                font-size: 9px;
                padding: 3px 0;
            }
            
            .mini-day-btn {
                height: 30px;
                font-size: 11px;
            }
            
            .mini-day-dots .dot {
                width: 3px;
                height: 3px;
            }
            
            .filter-section-header {
                margin-bottom: 6px;
            }
            
            .filter-title {
                font-size: 10px;
            }
            
            .filter-checkbox {
                font-size: 12px;
                padding: 5px 6px;
            }
            
            .checkmark {
                width: 16px;
                height: 16px;
            }
            
            .stat-row {
                font-size: 11px;
                padding: 4px 0;
                gap: 6px;
            }
            
            .stat-dot {
                width: 6px;
                height: 6px;
            }
            
            .main-header {
                padding: 10px 12px;
            }
            
            .main-header h1 {
                font-size: 16px;
            }
            
            .btn-today {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .main-day-names .day-name {
                padding: 8px 2px;
                font-size: 9px;
                font-weight: 600;
            }
            
            .main-calendar-grid {
                grid-template-rows: repeat(auto-fill, minmax(90px, 1fr));
            }
            
            .main-day-cell {
                min-height: 90px;
                padding: 4px;
            }
            
            .day-header {
                margin-bottom: 3px;
            }
            
            .day-number {
                width: 22px;
                height: 22px;
                font-size: 10px;
            }
            
            .event-more-badge {
                font-size: 7px;
                padding: 1px 4px;
                border-radius: 3px;
            }
            
            .event-item {
                margin-bottom: 2px;
                border-left-width: 2px;
            }
            
            .event-content {
                padding: 3px 4px;
            }
            
            .event-header {
                gap: 2px;
                margin-bottom: 1px;
            }
            
            .event-time {
                font-size: 8px;
                font-weight: 600;
            }
            
            .event-type-badge {
                font-size: 6px;
                padding: 1px 2px;
                border-radius: 2px;
            }
            
            .event-location {
                font-size: 7px;
                margin-top: 1px;
            }
            
            .event-location svg {
                width: 7px;
                height: 7px;
            }
            
            /* Modal for mobile */
            .modal-content {
                width: 98%;
                max-width: none;
                max-height: 90vh;
                border-radius: 12px 12px 0 0;
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                animation: modalSlideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            @keyframes modalSlideUp {
                from {
                    transform: translateY(100%);
                }
                to {
                    transform: translateY(0);
                }
            }
            
            .modal-header {
                padding: 14px 16px;
                background: #f8f9fa;
            }
            
            .modal-title {
                font-size: 15px;
            }
            
            .modal-close {
                padding: 6px;
            }
            
            .modal-close svg {
                width: 18px;
                height: 18px;
            }
            
            .modal-body {
                padding: 12px 16px;
            }
            
            .modal-event-item {
                padding: 10px;
                margin-bottom: 8px;
                border-left-width: 3px;
            }
            
            .modal-event-header {
                margin-bottom: 6px;
            }
            
            .modal-event-type {
                font-size: 9px;
                padding: 2px 6px;
            }
            
            .modal-event-status {
                font-size: 9px;
            }
            
            .modal-event-title {
                font-size: 12px;
                margin-bottom: 3px;
            }
            
            .modal-event-location {
                font-size: 10px;
                gap: 4px;
            }
            
            .modal-event-location svg {
                width: 12px;
                height: 12px;
            }
        }
        
        /* Mobile Small */
        @media (max-width: 480px) {
            .schedule-sidebar {
                padding: 8px;
            }
            
            .mini-calendar {
                margin-bottom: 8px;
            }
            
            .mini-calendar-header {
                margin-bottom: 8px;
            }
            
            .mini-calendar-header h3 {
                font-size: 13px;
            }
            
            .nav-btn {
                width: 26px;
                height: 26px;
            }
            
            .nav-btn svg {
                width: 12px;
                height: 12px;
            }
            
            .mini-calendar-day-names {
                margin-bottom: 4px;
            }
            
            .mini-calendar-day-names > div {
                font-size: 8px;
            }
            
            .mini-day-btn {
                height: 26px;
                font-size: 10px;
            }
            
            .mini-day-dots .dot {
                width: 2px;
                height: 2px;
            }
            
            .filter-options {
                gap: 3px;
            }
            
            .filter-section {
                margin-bottom: 8px;
            }
            
            .filter-title {
                font-size: 9px;
                margin-bottom: 4px;
            }
            
            .filter-checkbox {
                font-size: 11px;
                padding: 4px 5px;
                gap: 5px;
            }
            
            .checkmark {
                width: 15px;
                height: 15px;
            }
            
            .checkmark::after {
                width: 3px;
                height: 7px;
            }
            
            .stat-row {
                font-size: 10px;
                gap: 5px;
                padding: 3px 0;
            }
            
            .stat-dot {
                width: 5px;
                height: 5px;
            }
            
            .stats-title {
                font-size: 9px;
            }
            
            .main-header {
                padding: 8px 10px;
            }
            
            .main-header h1 {
                font-size: 14px;
            }
            
            .btn-today {
                padding: 5px 10px;
                font-size: 11px;
            }
            
            .main-day-names .day-name {
                padding: 6px 2px;
                font-size: 8px;
            }
            
            .main-calendar-grid {
                grid-template-rows: repeat(auto-fill, minmax(80px, 1fr));
            }
            
            .main-day-cell {
                min-height: 80px;
                padding: 3px;
            }
            
            .day-header {
                margin-bottom: 2px;
            }
            
            .day-number {
                width: 20px;
                height: 20px;
                font-size: 9px;
            }
            
            .event-more-badge {
                font-size: 6px;
                padding: 1px 3px;
            }
            
            .event-item {
                margin-bottom: 2px;
            }
            
            .event-content {
                padding: 2px 3px;
            }
            
            .event-header {
                margin-bottom: 0;
                gap: 2px;
            }
            
            .event-time {
                font-size: 7px;
            }
            
            .event-type-badge {
                font-size: 5px;
                padding: 0px 2px;
            }
            
            .event-location {
                display: none;
            }
            
            .modal-content {
                width: 100%;
                max-height: 95vh;
            }
            
            .modal-header {
                padding: 12px 14px;
            }
            
            .modal-title {
                font-size: 14px;
            }
            
            .modal-close {
                padding: 5px;
            }
            
            .modal-close svg {
                width: 16px;
                height: 16px;
            }
            
            .modal-body {
                padding: 10px 14px;
            }
            
            .modal-event-item {
                padding: 8px;
                margin-bottom: 6px;
            }
            
            .modal-event-header {
                margin-bottom: 4px;
            }
            
            .modal-event-type {
                font-size: 8px;
                padding: 1px 4px;
            }
            
            .modal-event-status {
                font-size: 8px;
            }
            
            .modal-event-title {
                font-size: 11px;
                margin-bottom: 2px;
            }
            
            .modal-event-location {
                font-size: 9px;
                gap: 3px;
            }
            
            .modal-event-location svg {
                width: 10px;
                height: 10px;
            }
        }
        
        /* Extra Small Mobile */
        @media (max-width: 360px) {
            body {
                zoom: 100%;
            }
            
            .mini-day-btn {
                height: 24px;
                font-size: 9px;
            }
            
            .main-day-cell {
                min-height: 70px;
            }
            
            .day-number {
                width: 18px;
                height: 18px;
                font-size: 8px;
            }
            
            .event-time {
                font-size: 6px;
            }
            
            .event-type-badge {
                font-size: 4px;
            }
        }
    
        
        /* ===== Enhanced Responsive Layout ===== */
        html, body {
            width: 100%;
            overflow-x: hidden;
        }

        .public-schedule-page {
            width: 100%;
            min-height: 100vh;
        }

        .schedule-container {
            width: 100%;
            min-height: 100vh;
        }

        .schedule-main {
            min-width: 0;
        }

        .main-calendar {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
        }

        .main-calendar-grid {
            width: 100%;
        }

        .event-item,
        .modal-event-item,
        .filter-checkbox,
        .btn-today,
        .nav-btn {
            -webkit-tap-highlight-color: transparent;
        }

        .event-header,
        .modal-event-header,
        .day-header,
        .main-header {
            gap: 8px;
        }

        .event-time,
        .event-location span,
        .modal-event-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 991px) {
            .schedule-container {
                flex-direction: column;
            }

            .schedule-sidebar {
                width: 100%;
                max-width: 100%;
                border-right: none;
                border-bottom: 1px solid #dadce0;
            }

            .schedule-main {
                width: 100%;
            }

            .main-calendar-grid {
                min-width: 700px;
            }

            .main-calendar {
                overflow-x: auto;
                overflow-y: auto;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }

            .schedule-sidebar {
                padding: 12px;
            }

            .main-header {
                flex-wrap: wrap;
                align-items: flex-start;
            }

            .main-header h1 {
                width: 100%;
            }

            .btn-today {
                width: 100%;
                text-align: center;
            }

            .sidebar-filters {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .stat-container {
                flex-direction: column;
                gap: 2px;
            }

            .modal-content {
                width: calc(100% - 16px);
                margin: 8px;
            }
        }

        @media (max-width: 576px) {
            .main-calendar-grid {
                min-width: 640px;
            }

            .main-day-cell,
            .main-day-empty {
                min-height: 82px;
            }

            .event-content {
                padding: 2px 4px;
            }

            .event-type-badge {
                line-height: 1.2;
            }

            .modal-content {
                border-radius: 16px 16px 0 0;
            }
        }

        @media (max-width: 420px) {
            .schedule-sidebar {
                padding: 8px;
            }

            .main-header {
                padding: 10px;
            }

            .main-header h1 {
                font-size: 15px;
            }

            .btn-today {
                font-size: 11px;
                padding: 6px 10px;
            }

            .filter-checkbox {
                font-size: 11px;
            }

            .main-calendar-grid {
                min-width: 600px;
            }
        }

    
        
        /* ===== Modern Responsive Layout ===== */

        .schedule-container {
            background: #f3f4f6;
        }

        .schedule-sidebar {
            background: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.03);
            z-index: 5;
        }

        .schedule-main {
            background: #f8fafc;
            position: relative;
        }

        .main-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid #e5e7eb;
        }

        .main-header-left {
            display: flex;
            align-items: center;
        }

        .main-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-fullscreen {
            border: none;
            background: #111827;
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fullscreen-exit {
            display: none;
        }

        .schedule-main.fullscreen-mode {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            background: #ffffff;
        }

        .schedule-main.fullscreen-mode .fullscreen-enter {
            display: none;
        }

        .schedule-main.fullscreen-mode .fullscreen-exit {
            display: inline;
        }

        .schedule-main.fullscreen-mode .main-calendar {
            height: calc(100vh - 80px);
        }

        .main-calendar {
            padding: 16px;
        }

        .main-day-names {
            background: #ffffff;
            border-radius: 14px 14px 0 0;
            overflow: hidden;
        }

        .main-calendar-grid {
            background: #ffffff;
            border-radius: 0 0 14px 14px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .event-item {
            border-radius: 8px;
            background: #ffffff;
        }

        .event-type-badge {
            border-radius: 999px;
        }

        .mini-calendar,
        .sidebar-filters,
        .sidebar-stats {
            background: #ffffff;
            border-radius: 14px;
            padding: 14px;
            border: 1px solid #ececec;
        }

        .sidebar-filters,
        .sidebar-stats {
            margin-top: 18px;
        }

        @media (max-width: 1024px) {
            .schedule-container {
                flex-direction: column;
            }

            .schedule-sidebar {
                width: 100%;
            }

            .main-calendar-grid {
                min-width: 820px;
            }
        }

        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                align-items: stretch;
            }

            .main-header-actions {
                width: 100%;
            }

            .btn-fullscreen,
            .btn-today {
                flex: 1;
                text-align: center;
            }

            .main-calendar {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .main-calendar-grid {
                min-width: 700px;
            }

            .btn-fullscreen,
            .btn-today {
                font-size: 11px;
            }
        }

    </style>

    {{-- Real-time Update Script --}}
    <script>
        // Make eventsByDate available globally for modal
        window.eventsByDate = @json($eventsByDate ?? []);
        
        (function() {
            const POLL_INTERVAL = 20000;
            let pollTimer = null;
            let isUpdating = false;
                
            // Filter state
            let activeFilters = {
                jenis: ['produksi', 'jasa'],
                statusProduksi: ['baru', 'proses', 'siap diambil', 'selesai'],
                statusJasa: ['jasa baru', 'terjadwal', 'selesai dikerjakan', 'selesai']
            };
                        
            // Initialize filters
            function initFilters() {
                const jenisCheckboxes = document.querySelectorAll('#filter-produksi, #filter-jasa');
                const statusCheckboxes = document.querySelectorAll('.status-filter');
                const toggleButtons = document.querySelectorAll('.filter-toggle-btn');
                
                // Toggle button functionality
                toggleButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const targetId = this.dataset.target;
                        const targetElement = document.getElementById(targetId);
                        
                        if (targetElement) {
                            targetElement.classList.toggle('collapsed');
                            this.classList.toggle('collapsed');
                        }
                    });
                });
                
                jenisCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            if (!activeFilters.jenis.includes(this.value)) {
                                activeFilters.jenis.push(this.value);
                            }
                        } else {
                            activeFilters.jenis = activeFilters.jenis.filter(j => j !== this.value);
                        }
                        
                        // Disable/enable status filters based on jenis selection
                        updateStatusFiltersState();
                        applyFilters();
                    });
                });
                
                statusCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const value = this.value;
                        const parentFilter = this.closest('.filter-options');
                        const isProduksiFilter = parentFilter && parentFilter.id === 'status-produksi-filter';
                        const isJasaFilter = parentFilter && parentFilter.id === 'status-jasa-filter';
                        
                        if (this.checked) {
                            // Add to the appropriate status array based on parent filter section
                            if (isProduksiFilter) {
                                if (!activeFilters.statusProduksi.includes(value)) {
                                    activeFilters.statusProduksi.push(value);
                                }
                            } else if (isJasaFilter) {
                                if (!activeFilters.statusJasa.includes(value)) {
                                    activeFilters.statusJasa.push(value);
                                }
                            }
                        } else {
                            // Remove from the appropriate status array based on parent filter section
                            if (isProduksiFilter) {
                                activeFilters.statusProduksi = activeFilters.statusProduksi.filter(s => s !== value);
                            } else if (isJasaFilter) {
                                activeFilters.statusJasa = activeFilters.statusJasa.filter(s => s !== value);
                            }
                        }
                        applyFilters();
                    });
                });
                
                // Initial state
                updateStatusFiltersState();
            }
            
            // Update status filters state based on jenis selection
            function updateStatusFiltersState() {
                const produksiCheckbox = document.getElementById('filter-produksi');
                const jasaCheckbox = document.getElementById('filter-jasa');
                const statusProduksiFilter = document.getElementById('status-produksi-filter');
                const statusJasaFilter = document.getElementById('status-jasa-filter');
                
                // Disable Produksi status filters if Produksi is unchecked
                if (statusProduksiFilter) {
                    if (produksiCheckbox && !produksiCheckbox.checked) {
                        statusProduksiFilter.classList.add('disabled');
                    } else {
                        statusProduksiFilter.classList.remove('disabled');
                    }
                }
                
                // Disable Jasa status filters if Jasa is unchecked
                if (statusJasaFilter) {
                    if (jasaCheckbox && !jasaCheckbox.checked) {
                        statusJasaFilter.classList.add('disabled');
                    } else {
                        statusJasaFilter.classList.remove('disabled');
                    }
                }
            }
                
            // Apply filters to calendar
            function applyFilters() {
                const eventItems = document.querySelectorAll('.event-item');
                    
                eventItems.forEach(item => {
                    const eventType = item.dataset.type;
                    const eventStatus = item.dataset.status;
                        
                    const jenisMatch = activeFilters.jenis.includes(eventType);
                    
                    // Check status based on event type
                    let statusMatch = false;
                    if (eventType === 'produksi') {
                        statusMatch = activeFilters.statusProduksi.includes(eventStatus);
                    } else if (eventType === 'jasa') {
                        statusMatch = activeFilters.statusJasa.includes(eventStatus);
                    }
                        
                    if (jenisMatch && statusMatch) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
                    
                // Update mini calendar visibility
                updateMiniCalendar();
            }
                
            // Update mini calendar based on filters
            function updateMiniCalendar() {
                const dayButtons = document.querySelectorAll('.mini-day-btn');
                    
                dayButtons.forEach(btn => {
                    const hasJasa = btn.dataset.hasJasa === '1';
                    const hasProduksi = btn.dataset.hasProduksi === '1';
                        
                    const showJasa = activeFilters.jenis.includes('jasa');
                    const showProduksi = activeFilters.jenis.includes('produksi');
                        
                    const shouldShow = (hasJasa && showJasa) || (hasProduksi && showProduksi);
                        
                    if (shouldShow || btn.classList.contains('selected') || btn.classList.contains('today')) {
                        btn.style.opacity = '1';
                    } else {
                        btn.style.opacity = '0.3';
                    }
                });
            }
    
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
    
                    // Update calendar grid
                    const newGrid = doc.querySelector('.main-calendar-grid');
                    const oldGrid = document.querySelector('.main-calendar-grid');
                    if (newGrid && oldGrid) {
                        oldGrid.style.opacity = '0.5';
                        oldGrid.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            oldGrid.innerHTML = newGrid.innerHTML;
                            oldGrid.style.opacity = '1';
                            // Wait a bit for DOM to update, then re-apply filters
                            setTimeout(() => {
                                applyFilters();
                                updateStatusFiltersState();
                            }, 50);
                        }, 300);
                    }
    
                    // Update mini calendar
                    const newMiniDays = doc.querySelector('.mini-calendar-days');
                    const oldMiniDays = document.querySelector('.mini-calendar-days');
                    if (newMiniDays && oldMiniDays) {
                        oldMiniDays.innerHTML = newMiniDays.innerHTML;
                        updateMiniCalendar();
                    }
    
                    // Update stats
                    const newStats = doc.querySelector('.sidebar-stats');
                    const oldStats = document.querySelector('.sidebar-stats');
                    if (newStats && oldStats) {
                        oldStats.innerHTML = newStats.innerHTML;
                    }
    
                    // Update month name
                    const newMonthName = doc.querySelector('.main-header h1');
                    const oldMonthName = document.querySelector('.main-header h1');
                    if (newMonthName && oldMonthName) {
                        oldMonthName.textContent = newMonthName.textContent;
                    }
    
                    console.log('✓ Schedule updated at', new Date().toLocaleTimeString());
                } catch (error) {
                    console.error(' Failed to fetch schedule updates:', error);
                } finally {
                    isUpdating = false;
                }
            }
    
            // Start polling
            function startPolling() {
                pollTimer = setInterval(fetchScheduleData, POLL_INTERVAL);
            }
    
            // Stop polling
            function stopPolling() {
                if (pollTimer) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                }
            }
            
            // Initialize modal
            function initModal() {
                const modal = document.getElementById('eventsModal');
                const overlay = document.getElementById('modalOverlay');
                const closeBtn = document.getElementById('modalClose');
                
                // Close modal functions
                function closeModal() {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Event listeners
                closeBtn.addEventListener('click', closeModal);
                overlay.addEventListener('click', closeModal);
                
                // Close on ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && modal.classList.contains('active')) {
                        closeModal();
                    }
                });
                
                // Click on "+X more" badge
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('event-more-badge')) {
                        const date = e.target.dataset.date;
                        openModal(date);
                    }
                });
                
                // Click on day cell (but not on event items)
                document.addEventListener('click', function(e) {
                    const dayCell = e.target.closest('.main-day-cell');
                    if (dayCell && !e.target.closest('.event-item') && !e.target.closest('.event-more-badge')) {
                        const date = dayCell.dataset.date;
                        const dayEvents = window.eventsByDate && window.eventsByDate[date];
                        // Open modal for any date cell click, even if no events
                        openModal(date);
                    }
                });
            }
            
            // Open modal with events
            function openModal(date) {
                const modal = document.getElementById('eventsModal');
                const modalDate = document.getElementById('modalDate');
                const modalBody = document.getElementById('modalBody');
                
                // Format date
                const dateObj = new Date(date);
                const formattedDate = dateObj.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                modalDate.textContent = formattedDate;
                
                // Get events for this date
                const events = window.eventsByDate && window.eventsByDate[date];
                
                if (!events || events.length === 0) {
                    modalBody.innerHTML = '<p style="text-align: center; color: #5f6368; padding: 20px;">No events for this date</p>';
                } else {
                    modalBody.innerHTML = events.map(event => {
                        const statusClass = event.status.replace(/ /g, '-');
                        return `
                            <div class="modal-event-item ${event.type} ${statusClass}">
                                <div class="modal-event-header">
                                    <span class="modal-event-type ${event.type}">
                                        ${event.type === 'jasa' ? 'Jasa' : 'Produksi'}
                                    </span>
                                    <span class="modal-event-status">${event.status}</span>
                                </div>
                                <div class="modal-event-title">${event.title}</div>
                                ${event.location ? `
                                    <div class="modal-event-location">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>${event.location}</span>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }).join('');
                }
                
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
    
            
            
            // Fullscreen Toggle
            const fullscreenBtn = document.getElementById('toggleFullscreen');
            const scheduleMain = document.querySelector('.schedule-main');

            if (fullscreenBtn && scheduleMain) {
                fullscreenBtn.addEventListener('click', function () {
                    scheduleMain.classList.toggle('fullscreen-mode');

                    if (scheduleMain.classList.contains('fullscreen-mode')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            }


            // Initialize when page loads
            initFilters();
            initModal();
            startPolling();
    
            // Stop when page is hidden
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopPolling();
                } else {
                    startPolling();
                    fetchScheduleData();
                }
            });
    
            // Cleanup on page unload
            window.addEventListener('beforeunload', stopPolling);
        })();
    </script>
</div>

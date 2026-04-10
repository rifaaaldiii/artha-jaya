@push('head')
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
@endpush

<x-filament-panels::page>
    <style>
        :root {
            --aj-surface: #ffffff;
            --aj-card-bg: #ffffff;
            --aj-card-border: #e5e7eb;
            --aj-card-divider: #f3f4f6;
            --aj-text: #111827;
            --aj-muted: #6b7280;
            --aj-step-completed: #22c55e;
            --aj-step-current: #22c55e;
            --aj-step-current-ring: rgba(51, 65, 85, 0.12);
            --aj-step-upcoming-bg: #e5e7eb;
            --aj-step-upcoming-text: #94a3b8;
            --aj-step-connector: #e5e7eb;
            --aj-soft-bg: #f8fafc;
            --aj-status-card-bg: linear-gradient(135deg, #f0fdfa, #ecfeff);
            --aj-status-card-border: #e2e8f0;
            --aj-status-title: #0f172a;
            --aj-status-subtitle: #475569;
            --aj-select-border: #cbd5f5;
            --aj-select-bg: #ffffff;
            --aj-select-text: #111827;
            --aj-note: #dc2626;
            --aj-role-indicator: #f97316;
            --aj-role-indicator-dot: #fb923c;
            --aj-realtime-text: #6b7280;
        }

        .dark,
        [data-theme="dark"],
        .filament-theme-dark {
            --aj-surface: #0f172a;
            --aj-card-bg: #0b1120;
            --aj-card-border: #1f2937;
            --aj-card-divider: #1e293b;
            --aj-text: #f8fafc;
            --aj-muted: #94a3b8;
            --aj-step-completed: #22c55e;
            --aj-step-current: #22c55e;
            --aj-step-current-ring: rgba(51, 65, 85, 0.12);
            --aj-step-upcoming-bg: #e5e7eb;
            --aj-step-upcoming-text: #94a3b8;
            --aj-step-connector: #e5e7eb;
            --aj-soft-bg: #0f172a;
            --aj-status-card-bg: linear-gradient(135deg, #0f172a, #0b1120);
            --aj-status-card-border: #1e293b;
            --aj-status-title: #e2e8f0;
            --aj-status-subtitle: #94a3b8;
            --aj-select-border: #334155;
            --aj-select-bg: #0f172a;
            --aj-select-text: #f8fafc;
            --aj-note: #f87171;
            --aj-role-indicator: #fdba74;
            --aj-role-indicator-dot: #fb923c;
            --aj-realtime-text: #cbd5f5;
        }

        .mb-6 { margin-bottom: 24px; }
        .max-w-md { max-width: 440px; }
        .block { display: block; }
        .role-action-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 6px;
            font-size: 12px;
            color: var(--aj-role-indicator);
            font-weight: 600;
        }
        .role-action-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--aj-role-indicator-dot);
            animation: pulse 2s infinite;
            box-shadow: 0 0 0 0 rgba(251, 146, 60, 0.35);
        }
        [x-cloak] { display: none !important; }
        .progress-container { display: flex; gap: 32px; }
        .prog-sidebar { width: 260px; flex-shrink: 0; }
        .prog-steps { display: flex; flex-direction: column; gap: 8px;}
        .prog-step-row { display: flex; align-items: flex-start; gap: 16px; }
        .prog-step-circles-col { display: flex; flex-direction: column; align-items: center;}
        .prog-step-circle, .prog-step-circle-current, .prog-step-circle-upcoming {
            width: 35px; height: 35px; border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
        }
        .prog-step-circle { background: var(--aj-step-completed); }
        .prog-step-circle svg { width: 24px; height: 24px; color: #fff; }
        .prog-step-circle-current {
            background: var(--aj-step-current);
            box-shadow: 0 0 0 4px var(--aj-step-current-ring);
            color: #fff;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
          0% { box-shadow: 0 0 0 0 #22c55e70;}
          70% { box-shadow: 0 0 0 6px #22c55e10;}
          100% { box-shadow: 0 0 0 0 #22c55e00;}
        }
        .prog-step-circle-upcoming {
            background: var(--aj-step-upcoming-bg);
            color: var(--aj-step-upcoming-text);
            font-weight: bold;
        }
        .prog-connector {
            width: 3px; height: 64px; margin-top: 10px;
            background: var(--aj-step-connector);
        }
        .prog-connector.completed { background: var(--aj-step-completed); }
        .prog-step-label {
            font-weight: bold; font-size: 16px;
            color: var(--aj-text);
        }
        .prog-step-label.current { color: var(--aj-step-current); }
        .prog-step-label.completed { color: var(--aj-step-completed); }
        .prog-step-label.upcoming { color: var(--aj-step-upcoming-text); }
        .prog-step-subtitle {
            font-size: 14px;
            color: var(--aj-muted);
            margin-top: 4px;
        }

        .detail-card {
            background: var(--aj-card-bg);
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            border: 1px solid var(--aj-card-border);
        }
        .detail-header {
            background: transparent;
            color: var(--aj-text);
            padding: 22px 24px 12px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 1px solid var(--aj-card-divider);
        }
        .detail-header-icon {
            width: 44px; height: 44px;
            background: var(--aj-soft-bg);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .detail-header-title {
            font-size: 1.20rem;
            font-weight: 700;
            margin-bottom: 1px;
            letter-spacing: -.5px;
        }
        .detail-header-status {
            margin-top: 7px;
        }
        .detail-main {
            padding: 16px 24px 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: transparent;
        }
        .detail-list {
            list-style: none;
            padding: 0;
            margin: 0 0 4px 0;
        }
        .detail-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 15.2px;
            color: var(--aj-text);
        }
        .detail-list-item:last-child { border-bottom: none; }
        .detail-item-label {
            color: var(--aj-muted);
            font-size: 14px;
            font-weight: 500;
            margin-right: 18px;
            flex-shrink: 0;
        }
        .detail-item-value {
            color: var(--aj-text);
            font-size: 15.6px;
            font-weight: 600;
            text-align: right;
            flex: 1;
            word-break: break-word;
        }
        .status-badge { display: inline-flex; align-items: center; padding: 2px 15px; border-radius: 999px; font-size: 14px; font-weight: 500;}
        .status-red { background: #fee2e2; color: #991b1b;}
        .status-blue { background: #dbeafe; color: #1e3a8a;}
        .status-yellow { background: #fef9c3; color: #92400e;}
        .status-green { background: #d1fae5; color: #166534;}
        .status-indigo { background: #ddd6fe; color: #3730a3;}
        .status-purple { background: #f3e8ff; color: #5b21b6;}
        .status-bg-default { background: #bbf7d0; color: #166534;}
        .detail-note {
            color: var(--aj-note);
            font-size: 13.2px;
            font-style: italic;
            word-break: break-word;
            margin-top: 1px;
        }
        .realtime-info {
            display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--aj-realtime-text); margin-top: 16px;
        }
        .pulse-dot {
            width: 9px; height: 9px; background: #22c55e; border-radius: 50%;
            animation: pulse 2s infinite;
        }
        .whitespace-pre-wrap { white-space: pre-wrap; }

        /* Info Grid Layout */
        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .info-section {
            background: var(--aj-card-bg);
            border-radius: 10px;
            padding: 16px;
            border: 1px solid var(--aj-card-border);
        }
        .info-section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-text);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--aj-card-divider);
        }
        .items-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .item-card {
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .item-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .item-card-header {
            background: #f3f4f6;
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .item-badge {
            display: inline-block;
            background: #22c55e;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
        }
        .item-card-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
        }
        .item-row:not(:last-child) {
            border-bottom: 1px solid #f3f4f6;
        }
        .item-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }
        .item-value {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
            text-align: right;
        }
        .item-price {
            color: #22c55e;
            font-weight: 700;
        }

        /* Image Gallery */
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }
        .image-gallery-item {
            position: relative;
            cursor: pointer;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            transition: all 0.2s;
        }
        .image-gallery-item:hover {
            border-color: #22c55e;
            transform: scale(1.05);
        }
        .image-gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        /* Image Modal */
        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            padding: 20px;
            animation: fadeIn 0.3s;
        }
        .image-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-modal-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
            animation: zoomIn 0.3s;
        }
        .image-modal-content img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
        }
        .image-modal-close {
            position: fixed;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 36px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10001;
        }
        .image-modal-close:hover {
            background: rgba(220, 38, 38, 0.8);
            border-color: white;
            transform: rotate(90deg);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        @media (max-width: 1024px) {
            .progress-container { gap: 16px; }
            .prog-sidebar { width: 200px; }
            .detail-main { padding: 12px 14px 16px 14px; }
        }
        @media (max-width: 768px) {
            .progress-container { flex-direction: column; gap: 20px; }
            .prog-sidebar { width: 100%; max-width: 100vw; margin-bottom: 10px; }
            .detail-header { padding: 16px 10px 8px 12px; }
            .detail-main { padding: 10px 10px 14px 10px; }
        }
        @media (max-width: 600px) {
            .detail-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        }
        /* Update status controls */
        .update-status-card {
            margin-top: 18px;
            border: 1px solid var(--aj-status-card-border);
            border-radius: 14px;
            background: var(--aj-status-card-bg);
            padding: 18px 20px 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .update-status-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }
        .update-status-title {
            font-weight: 600;
            font-size: 16px;
            color: var(--aj-status-title);
        }
        .update-status-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: var(--aj-status-subtitle);
        }
        .update-status-body {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .update-status-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .update-status-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--aj-status-title);
        }
        .update-status-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        /* Image upload section */
        .image-upload-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .image-upload-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--aj-status-title);
        }
        @media (max-width: 640px) {
            .update-status-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .update-status-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    {{-- Pilih Jasa --}}
    <div class="mb-6">
        <div class="max-w-md">
            {{ $this->jasaForm }}
        </div>
    </div>

    @if(!$record)
        <div class="detail-card">
            <div class="detail-header" style="background: transparent;">
                <div>
                    <div class="detail-header-title">Pilih Jasa</div>
                    <div style="font-size:1rem; color:#adb5bd;">Silakan pilih jasa untuk melihat progress.</div>
                </div>
            </div>
        </div>
    @else
        @php
            $statuses = [
                'jasa baru' => [
                    'label' => 'Jasa Baru',
                    'subtitle' => 'Pesanan jasa baru masuk (Admin Toko)',
                    'step' => 1,
                ],
                'terjadwal' => [
                    'label' => 'Terjadwal',
                    'subtitle' => 'Sudah dijadwalkan (Kepala Teknisi)',
                    'step' => 2,
                ],
                'selesai dikerjakan' => [
                    'label' => 'Selesai Dikerjakan',
                    'subtitle' => 'Petugas selesai mengerjakan (Petugas)',
                    'step' => 3,
                ],
                'selesai' => [
                    'label' => 'Selesai',
                    'subtitle' => 'Jasa selesai (Admin Toko)',
                    'step' => 4,
                ],
            ];
            $currentStatus = $record->status;
            $currentStep = $statuses[$currentStatus]['step'] ?? 1;
            $nextStatus = $this->getNextSequentialStatusProperty();
            $allowedStatuses = $this->getAllowedStatusesForRole();
        @endphp

        <div class="progress-container">
            {{-- Left Side: Progress Stepper --}}
            <div class="prog-sidebar">
                <div class="prog-steps">
                    @foreach($statuses as $statusKey => $statusInfo)
                        @php
                            $step = $statusInfo['step'];
                            $isCompleted = $currentStep > $step;
                            $isCurrent = $currentStep === $step;
                            $showRoleIndicator = $nextStatus && $nextStatus === $statusKey && in_array($statusKey, $allowedStatuses, true);
                        @endphp

                        <div class="prog-step-row">
                            <div class="prog-step-circles-col">
                                <div>
                                    @if($isCompleted)
                                        <div class="prog-step-circle">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @elseif($isCurrent)
                                        <div class="prog-step-circle-current">
                                            <span>{{ $step }}</span>
                                        </div>
                                    @else
                                        <div class="prog-step-circle-upcoming">
                                            <span>{{ $step }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                    <div class="prog-connector{{ $isCompleted ? ' completed' : '' }}"></div>
                                @endif
                            </div>
                            <div style="flex:1;padding-top:4px;">
                                <div class="prog-step-label
                                    @if($isCurrent) current @elseif($isCompleted) completed @else upcoming @endif">
                                    {{ $statusInfo['label'] }}
                                    @if($showRoleIndicator)
                                        <span class="role-action-indicator">
                                            <span class="role-action-dot"></span>
                                            <span>Bisa Anda lanjutkan</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="prog-step-subtitle">
                                    {{ $statusInfo['subtitle'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Side: Details --}}
            <div style="flex:1;">
                <div class="detail-card">
                    <div class="detail-header">
                        <div style="flex:2;">
                            <div class="detail-header-title">Detail Jasa</div>
                            <div class="detail-header-status">
                                @php
                                    $badgeClass = match($currentStep) {
                                        1 => 'status-badge status-red',
                                        2 => 'status-badge status-blue',
                                        3 => 'status-badge status-yellow',
                                        4 => 'status-badge status-green',
                                        default => 'status-badge status-bg-default',
                                    };
                                @endphp
                                <span class="{{ $badgeClass }}">{{ $statuses[$currentStatus]['label'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="detail-main">
                        <div class="info-grid">
                            {{-- Primary Info --}}
                            <div class="info-section">
                                <div class="info-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                    Informasi Jasa
                                </div>
                                <ul class="detail-list">
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">No. Jasa</span>
                                        <span class="detail-item-value">{{ $record->no_jasa }}</span>
                                    </li>
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">No. Ref</span>
                                        <span class="detail-item-value">{{ $record->no_ref ?? '-' }}</span>
                                    </li>
                                    @if($record->pelanggan)
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">Pelanggan</span>
                                        <span class="detail-item-value">{{ $record->pelanggan->nama }}</span>
                                    </li>
                                    @endif
                                    @if($record->jadwal_petugas)
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">Jadwal Petugas</span>
                                        <span class="detail-item-value">{{ $record->jadwal_petugas->format('d F Y, H:i') }} WIB</span>
                                    </li>
                                    @endif
                                    @if($record->petugasMany->isNotEmpty())
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">Petugas</span>
                                        <span class="detail-item-value">{{ $record->petugasMany->pluck('nama')->join(', ') }}</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Items Section --}}
                            @if($record->items && $record->items->count() > 0)
                            <div class="info-section">
                                <div class="info-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                    </svg>
                                    Detail Item ({{ $record->items->count() }})
                                </div>
                                <div class="items-container">
                                    @foreach($record->items as $index => $item)
                                    <div class="item-card">
                                        <div class="item-card-header">
                                            <span class="item-badge">Item {{ $index + 1 }}</span>
                                        </div>
                                        <div class="item-card-body">
                                            <div class="item-row">
                                                <span class="item-label">Jenis Jasa</span>
                                                <span class="item-value">{{ $item->nama_jasa }}</span>
                                            </div>
                                            @if($item->deskripsi)
                                            <div class="item-row">
                                                <span class="item-label">Deskripsi</span>
                                                <span class="item-value">{{ $item->deskripsi }}</span>
                                            </div>
                                            @endif
                                            <div class="item-row">
                                                <span class="item-label">Jumlah</span>
                                                <span class="item-value">{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="item-row">
                                                <span class="item-label">Harga</span>
                                                <span class="item-value item-price">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Progress Images --}}
                            @if(is_array($record->progress_images) && count($record->progress_images) > 0)
                            <div class="info-section">
                                <div class="info-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    Foto Progress ({{ count($record->progress_images) }})
                                </div>
                                <div class="image-gallery">
                                    @foreach($record->progress_images as $index => $image)
                                    <div class="image-gallery-item" onclick="window.openImageModal('{{ $this->getImageUrl($image['path']) }}', {{ $index }}, {{ count($record->progress_images) }})">
                                        <img src="{{ $this->getImageUrl($image['path']) }}" alt="Progress {{ $index + 1 }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Timestamps --}}
                            <div class="info-section">
                                <div class="info-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Waktu
                                </div>
                                <ul class="detail-list">
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">Tanggal Dibuat</span>
                                        <span class="detail-item-value">{{ $record->createdAt ? $record->createdAt->format('d F Y, H:i') : '-' }} WIB</span>
                                    </li>
                                    @if($record->updateAt)
                                    <li class="detail-list-item">
                                        <span class="detail-item-label">Terakhir Diupdate</span>
                                        <span class="detail-item-value">{{ $record->updateAt->format('d F Y, H:i') }} WIB</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Note --}}
                            @if($record->catatan)
                            <div class="info-section">
                                <div class="info-section-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                    </svg>
                                    Catatan
                                </div>
                                <div class="detail-note whitespace-pre-wrap">{{ $record->catatan }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Update Status Section --}}
                        @if($record->status !== 'selesai' && $nextStatus && in_array($nextStatus, $allowedStatuses))
                        <div class="update-status-card">
                            <div class="update-status-header">
                                <div>
                                    <div class="update-status-title">Update Status</div>
                                    <div class="update-status-subtitle">Lanjutkan ke status berikutnya</div>
                                </div>
                            </div>
                            <div class="update-status-body">
                                {{-- Terjadwal Form --}}
                                @if($nextStatus === 'terjadwal')
                                <div class="update-status-field">
                                    <div class="update-status-label">Form Penjadwalan</div>
                                    {{ $this->terjadwalForm }}
                                </div>
                                @endif

                                {{-- Image Upload --}}
                                <div class="image-upload-section">
                                    <div class="image-upload-label">Upload Foto (Opsional)</div>
                                    {{ $this->imageUploadForm }}
                                </div>

                                <div class="update-status-actions">
                                    <button wire:click="updateStatus"
                                            wire:confirm="Apakah Anda yakin ingin mengubah status menjadi {{ ucwords($nextStatus) }}?"
                                            style="padding: 11px 28px; border-radius: 999px; border: none; background: #22c55e; color: white; font-size: 14px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.35);">
                                        Update ke {{ ucwords($nextStatus) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Image Modal --}}
    <div id="imageModal" class="image-modal" onclick="window.closeImageModal(event)">
        <button class="image-modal-close" onclick="window.closeImageModal()">×</button>
        <div class="image-modal-content">
            <img id="modalImage" src="" alt="Preview">
        </div>
    </div>

    <script>
        window.openImageModal = function(url) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = url;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        window.closeImageModal = function(event) {
            if (event && event.target !== event.currentTarget && event.target.closest('button') === null) return;
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeImageModal();
            }
        });

        // Track file upload status
        window.trackUploadStatus = function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: true } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: false } }));
                    }
                });
            });

            document.addEventListener('filament:file-upload:completed', () => {
                setTimeout(() => {
                    const hasFiles = Array.from(fileInputs).some(input => input.files && input.files.length > 0);
                    window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: hasFiles } }));
                }, 500);
            });

            document.addEventListener('filament:file-upload:deleted', () => {
                setTimeout(() => {
                    const hasFiles = Array.from(fileInputs).some(input => input.files && input.files.length > 0);
                    window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: hasFiles } }));
                }, 300);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => window.trackUploadStatus(), 500);
            });
        } else {
            setTimeout(() => window.trackUploadStatus(), 500);
        }
    </script>
</x-filament-panels::page>

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
        .prog-step-circle-upcoming {
            background: var(--aj-step-upcoming-bg); color: var(--aj-step-upcoming-text); font-weight: bold;
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
        @keyframes pulse {
          0% { box-shadow: 0 0 0 0 #22c55e70;}
          70% { box-shadow: 0 0 0 6px #22c55e10;}
          100% { box-shadow: 0 0 0 0 #22c55e00;}
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
            color: var(--aj-text);
            font-weight: 600;
            text-align: right;
        }
        .item-price {
            color: #22c55e;
        }
        .catatan-section {
            background: #fef2f2;
            border-color: #fecaca;
        }

        /* Progress Images Gallery */
        .progress-images-section {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--aj-card-divider);
        }
        .progress-images-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--aj-text);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .progress-images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
        }
        .progress-image-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid var(--aj-card-border);
            transition: all 0.2s ease;
            background: var(--aj-soft-bg);
        }
        .progress-image-item:hover {
            border-color: #22c55e;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }
        .progress-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .progress-image-badge {
            position: absolute;
            bottom: 4px;
            right: 4px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        /* Image Modal/Lightbox */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s ease;
        }
        .image-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
            animation: zoomIn 0.3s ease;
        }
        .image-modal-content img {
            max-width: 100%;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        .image-modal-close {
            position: absolute;
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
            line-height: 1;
            z-index: 10001;
        }
        .image-modal-close:hover {
            background: rgba(220, 38, 38, 0.8);
            border-color: white;
            transform: rotate(90deg);
        }
        .image-modal-info {
            margin-top: 16px;
            text-align: center;
            color: white;
            background: rgba(0, 0, 0, 0.6);
            padding: 12px 20px;
            border-radius: 8px;
        }
        .image-modal-counter {
            font-size: 13px;
            opacity: 0.8;
            margin-bottom: 4px;
        }
        .image-modal-status {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }
        .image-modal-date {
            font-size: 13px;
            opacity: 0.8;
        }
        /* Navigation arrows */
        .image-modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 48px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            z-index: 10001;
        }
        .image-modal-nav:hover {
            background: rgba(34, 197, 94, 0.8);
            border-color: white;
            transform: translateY(-50%) scale(1.1);
        }
        .image-modal-prev {
            left: 20px;
        }
        .image-modal-next {
            right: 20px;
        }
        @media (max-width: 768px) {
            .image-modal-nav {
                width: 44px;
                height: 44px;
                font-size: 36px;
            }
            .image-modal-prev {
                left: 10px;
            }
            .image-modal-next {
                right: 10px;
            }
            .image-modal-close {
                top: 10px;
                right: 10px;
                width: 36px;
                height: 36px;
                font-size: 28px;
            }
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
        .image-upload-wrapper {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        @media (max-width: 768px) {
            .image-upload-wrapper {
                flex-direction: column;
            }
        }
        .update-status-select {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--aj-select-border);
            background: var(--aj-select-bg);
            color: var(--aj-select-text);
            font-size: 14px;
            min-width: 230px;
            box-shadow: inset 0 1px 2px rgba(15,23,42,0.04);
        }
        .update-status-select:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56,189,248,0.25);
        }
        .update-status-helper {
            font-size: 13px;
            color: var(--aj-status-title);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .update-status-helper svg {
            width: 16px;
            height: 16px;
        }
        .update-status-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .update-status-loading {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #0d9488;
            font-weight: 600;
        }
        @media (max-width: 640px) {
            .update-status-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .update-status-select {
                width: 100%;
            }
            .update-status-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <!-- Pilih Jasa -->
    <div class="mb-6">
        <div class="max-w-md">
            {{ $this->jasaForm }}
        </div>
    </div>

    @if(!$this->record)
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
                    'subtitle' => 'Permintaan baru masuk',
                    'step' => 1,
                ],
                'terjadwal' => [
                    'label' => 'Terjadwal',
                    'subtitle' => 'Tanggal dan petugas sudah ditentukan',
                    'step' => 2,
                ],
                'selesai dikerjakan' => [
                    'label' => 'Selesai Dikerjakan',
                    'subtitle' => 'Pekerjaan lapangan sudah selesai',
                    'step' => 3,
                ],
                'selesai' => [
                    'label' => 'Selesai',
                    'subtitle' => 'Jasa sudah diserahkan ke pelanggan',
                    'step' => 4,
                ],
            ];
            $currentStatus = $this->record->status;
            $currentStep = $statuses[$currentStatus]['step'] ?? 1;
            $allowedStatuses = $this->allowedStatuses ?? array_keys($statuses);
            $nextSequentialStatus = $this->nextSequentialStatus ?? null;
        @endphp

    <div class="progress-container">
        <!-- Left Side: Progress Stepper -->
        <div class="prog-sidebar">
            <div class="prog-steps">
                @foreach($statuses as $statusKey => $statusInfo)
                    @php
                        $step = $statusInfo['step'];
                        $isCompleted = $currentStep > $step;
                        $isCurrent = $currentStep === $step;

                        $selesaiStepIsCheck = false;
                        if($statusKey === 'selesai' && $this->record && $this->record->status === 'selesai') {
                            $selesaiStepIsCheck = true;
                        }
                    @endphp

                    <div class="prog-step-row">
                        <!-- Step Circle -->
                        <div class="prog-step-circles-col">
                            <div>
                                @if($step === 4 && $selesaiStepIsCheck)
                                    <!-- Special: Step 4 is checklist if status selesai -->
                                    <div class="prog-step-circle">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($isCompleted)
                                    <!-- Completed Step -->
                                    <div class="prog-step-circle">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($isCurrent)
                                    <!-- Current Step -->
                                    <div class="prog-step-circle-current">
                                        <span>{{ $step }}</span>
                                    </div>
                                @else
                                    <!-- Upcoming Step -->
                                    <div class="prog-step-circle-upcoming">
                                        <span>{{ $step }}</span>
                                    </div>
                                @endif
                            </div>
                            <!-- Connector Line -->
                            @if(!$loop->last)
                                <div class="prog-connector{{ $isCompleted ? ' completed' : '' }}"></div>
                            @endif
                        </div>
                        <!-- Step Content -->
                        <div style="flex:1;padding-top:4px;">
                            <div class="prog-step-label
                                @if($isCurrent) current @elseif($isCompleted || ($step === 4 && $selesaiStepIsCheck)) completed @else upcoming @endif">
                                {{ $statusInfo['label'] }}
                                @php
                                    $showRoleIndicator = $nextSequentialStatus && $nextSequentialStatus === $statusKey && in_array($statusKey, $allowedStatuses, true);
                                @endphp
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

        <!-- Right Side: Simplified Details -->
        <div style="flex:1;" >
            <div class="detail-card">
                <div class="detail-header">
                    <div style="flex:2;">
                        <div class="detail-header-title">
                            Detail Jasa
                        </div>
                        <div class="detail-header-status">
                            @php
                                $badgeClass =
                                    $currentStep === 1 ? 'status-badge status-blue' :
                                    ($currentStep === 2 ? 'status-badge status-yellow' :
                                    ($currentStep === 3 ? 'status-badge status-indigo' : 'status-badge status-green'));
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $statuses[$currentStatus]['label'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="detail-main">
                    {{-- Information Grid --}}
                    <div class="info-grid">
                        {{-- Primary Info Section --}}
                        <div class="info-section">
                            <div class="info-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Informasi Jasa
                            </div>
                            <ul class="detail-list">
                                <li class="detail-list-item">
                                    <span class="detail-item-label">No. Jasa</span>
                                    <span class="detail-item-value">{{ $this->record->no_jasa }}</span>
                                </li>
                                <li class="detail-list-item">
                                    <span class="detail-item-label">No. Ref</span>
                                    <span class="detail-item-value">{{ $this->record->no_ref }}</span>
                                </li>
                                <li class="detail-list-item">
                                    <span class="detail-item-label">Jadwal</span>
                                    <span class="detail-item-value">
                                        @if($this->record->jadwal_petugas)
                                            {{ $this->record->jadwal_petugas->format('d F Y, H:i') }} WIB
                                        @elseif($this->record->jadwal)
                                            {{ $this->record->jadwal->format('d F Y, H:i') }} WIB
                                        @else
                                            <span style="color: #9ca3af;">Belum dijadwalkan</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>

                        {{-- Items Section --}}
                        @if($this->record->items && $this->record->items->count() > 0)
                        <div class="info-section">
                            <div class="info-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                Detail Item ({{ $this->record->items->count() }})
                            </div>
                            <div class="items-container">
                                @foreach($this->record->items as $index => $item)
                                <div class="item-card">
                                    <div class="item-card-header">
                                        <span class="item-badge">Item {{ $index + 1 }}</span>
                                    </div>
                                    <div class="item-card-body">
                                        <div class="item-row">
                                            <span class="item-label">Jenis Layanan</span>
                                            <span class="item-value">{{ $item->jenis_layanan }}</span>
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

                        {{-- People Section --}}
                        <div class="info-section">
                            <div class="info-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Petugas & Pelanggan
                            </div>
                            <ul class="detail-list">
                                @if($this->record->petugasMany && $this->record->petugasMany->count() > 0)
                                <li class="detail-list-item">
                                    <span class="detail-item-label">Petugas</span>
                                    <span class="detail-item-value">
                                        {{ $this->record->petugasMany->pluck('nama')->join(', ') }}
                                    </span>
                                </li>
                                @elseif($this->record->petugas)
                                <li class="detail-list-item">
                                    <span class="detail-item-label">Petugas</span>
                                    <span class="detail-item-value">{{ $this->record->petugas->nama }}</span>
                                </li>
                                @endif
                                @if($this->record->pelanggan)
                                <li class="detail-list-item">
                                    <span class="detail-item-label">Pelanggan</span>
                                    <span class="detail-item-value">{{ $this->record->pelanggan->nama }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>

                        {{-- Timestamps Section --}}
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
                                    <span class="detail-item-value">{{ $this->record->createdAt ? $this->record->createdAt->format('d F Y, H:i') : '-' }} WIB</span>
                                </li>
                                @if($this->record->updateAt)
                                <li class="detail-list-item">
                                    <span class="detail-item-label">Terakhir Diupdate</span>
                                    <span class="detail-item-value">{{ $this->record->updateAt->format('d F Y, H:i')}} WIB</span>
                                </li>
                                @endif
                                <li class="detail-list-item">
                                    <span class="detail-item-label">Status</span>
                                    <span class="detail-item-value"><span class="{{ $badgeClass }}">{{ $statuses[$currentStatus]['label'] }}</span></span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Catatan Section --}}
                    @if($this->record->catatan)
                    <div class="info-section catatan-section">
                        <div class="info-section-title" style="color: #dc2626;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            Catatan
                        </div>
                        <div class="detail-note whitespace-pre-wrap">
                            {{ $this->record->catatan }}
                        </div>
                    </div>
                    @endif

                    {{-- Progress Images Gallery --}}
                    @if($this->record->progress_images && count($this->record->progress_images) > 0)
                    <div class="progress-images-section">
                        <div class="progress-images-title">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            Foto Progress ({{ count($this->record->progress_images) }})
                        </div>
                        
                        @php
                            $displayedCount = 0;
                            $missingCount = 0;
                        @endphp
                        
                        <div class="progress-images-grid">
                            @foreach($this->record->progress_images as $index => $imageData)
                                @php
                                    $imagePath = is_array($imageData) ? ($imageData['path'] ?? '') : $imageData;
                                    $statusFrom = is_array($imageData) ? ($imageData['status_from'] ?? '-') : '-';
                                    $statusTo = is_array($imageData) ? ($imageData['status_to'] ?? '-') : '-';
                                    $uploadedAt = is_array($imageData) ? ($imageData['uploaded_at'] ?? '-') : '-';
                                    $fullPath = $imagePath ? $this->getImageUrl($imagePath) : '';
                                    $fileExists = $imagePath && file_exists(storage_path('app/public/' . $imagePath));
                                    
                                    if ($fileExists) {
                                        $displayedCount++;
                                    } else {
                                        $missingCount++;
                                    }
                                @endphp
                                
                                @if($fileExists)
                                <div class="progress-image-item" onclick="openImageModal('{{ addslashes($fullPath) }}', '{{ addslashes($statusFrom . ' → ' . $statusTo) }}', '{{ addslashes($uploadedAt) }}', {{ $index }})">
                                    <img src="{{ $fullPath }}" alt="Progress Image {{ $index + 1 }}" loading="lazy">
                                    <div class="progress-image-badge">#{{ $index + 1 }}</div>
                                </div>
                                @else
                                {{-- Show placeholder for missing files --}}
                                <div class="progress-image-item" style="opacity: 0.5; cursor: not-allowed;" title="File tidak ditemukan: {{ $imagePath }}">
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #dc2626; font-size: 12px; text-align: center; padding: 8px;">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px; margin: 0 auto 4px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                            <div>Missing</div>
                                        </div>
                                    </div>
                                    <div class="progress-image-badge" style="background: #dc2626;">#{{ $index + 1 }}</div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                        @if($missingCount > 0)
                        <div style="margin-top: 12px; padding: 8px 12px; background: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 4px; font-size: 13px; color: #92400e;">
                            <strong>Perhatian:</strong> {{ $missingCount }} dari {{ count($this->record->progress_images) }} foto tidak ditemukan di storage. Mungkin file telah dihapus atau upload gagal.
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Tombol dan Dropdown untuk Update Status --}}
                    @php
                        $allowedStatuses = $this->allowedStatuses ?? array_keys($statuses);
                        $nextSequentialStatus = $this->nextSequentialStatus ?? null;
                        $canProceedNext = $nextSequentialStatus && in_array($nextSequentialStatus, $allowedStatuses, true);
                        // Tombol selalu enabled
                        $isUpdateDisabled = false;

                        // Temukan role yang diizinkan untuk status berikutnya (informasi jika tidak boleh melangkah)
                        $nextSequentialStatusRole = null;
                        $nextSequentialStatusRoleDisplay = '-';
                        if ($nextSequentialStatus) {
                            $roleStatusMap = [
                                'admin_toko' => ['produksi baru', 'selesai'],
                                'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
                                'kepala_teknisi_gudang' => ['dalam pengerjaan', 'lolos qc'],
                                'kepala_teknisi_lapangan' => ['terjadwal', 'sedang berjalan'],
                                'petugas' => ['selesai dikerjakan'],
                            ];
                            foreach ($roleStatusMap as $role => $statusesForRole) {
                                if (in_array($nextSequentialStatus, $statusesForRole, true)) {
                                    $nextSequentialStatusRole = $role;
                                    break;
                                }
                            }
                            // fallback jika tidak ditemukan, misal "administrator" dll
                            if (!$nextSequentialStatusRole) {
                                $nextSequentialStatusRole = 'administrator';
                            }
                            $nextSequentialStatusRoleDisplay = ucwords(str_replace('_', ' ', $nextSequentialStatusRole));
                        }
                    @endphp

                    @if($this->record->status !== 'selesai')
                        <div class="update-status-card">
                            <div class="update-status-header">
                                <div>
                                    <div class="update-status-title">Update Status Jasa</div>
                                    <p class="update-status-subtitle">
                                        Status hanya dapat bergerak ke langkah berikutnya.
                                    </p>
                                </div>
                            </div>

                            @if($canProceedNext)
                                <div class="update-status-body">
                                    {{-- Form khusus untuk update ke 'terjadwal' --}}
                                    @php
                                        $userRole = auth()->user()?->role;
                                        $normalizedUserRole = $userRole ? str_replace(' ', '_', strtolower($userRole)) : null;
                                        $isKepalaTeknisiLapangan = in_array($normalizedUserRole, ['kepala_teknisi_lapangan', 'admin_toko','administrator'], true);
                                    @endphp
                                    @if($nextSequentialStatus === 'terjadwal' && $isKepalaTeknisiLapangan)
                                        <div class="update-status-field terjadwal-form-wrapper">
                                            <label class="update-status-label">Langkah Berikutnya: Terjadwal</label>
                                            <p style="font-size: 13px; color: #64748b; margin-bottom: 12px;">
                                                Silakan isi jadwal petugas dan pilih petugas yang akan menangani jasa ini.
                                            </p>
                                            <div class="terjadwal-form-container">
                                                {{ $this->terjadwalForm }}
                                            </div>
                                            <div style="margin-top: 16px;">
                                                <x-filament::button
                                                    color="success"
                                                    icon="heroicon-m-check-badge"
                                                    wire:click="updateStatus"
                                                    wire:loading.attr="disabled"
                                                    wire:target="updateStatus"
                                                    :disabled="false"
                                                >
                                                    Simpan Status & Jadwalkan Petugas
                                                </x-filament::button>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Form normal untuk status lainnya --}}
                                        <div class="image-upload-wrapper">
                                            <!-- Status Update Field -->
                                            <div class="update-status-field" style="flex: 1; min-width: 200px;">
                                                <label class="update-status-label" for="updateStatusValue">Langkah Berikutnya</label>
                                                <div class="update-status-actions">
                                                    <select id="updateStatusValue"
                                                            class="update-status-select"
                                                            wire:model.defer="updateStatusValue"
                                                            wire:loading.attr="disabled"
                                                    >
                                                        <option value="">Konfirmasi status berikutnya</option>
                                                        <option value="{{ $nextSequentialStatus }}">
                                                            {{ $statuses[$nextSequentialStatus]['label'] }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Image Upload Field -->
                                            <div class="image-upload-section" style="flex: 1; min-width: 250px;">
                                                <div>
                                                    {{ $this->imageUploadForm }}
                                                </div>

                                                <x-filament::button
                                                    color="success"
                                                    icon="heroicon-m-check-badge"
                                                    wire:click="updateStatus"
                                                    wire:loading.attr="disabled"
                                                    wire:target="updateStatus"
                                                    :disabled="$this->isUploading"
                                                >
                                                    Simpan Status
                                                </x-filament::button>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="update-status-meta">
                                        <span class="update-status-helper">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                            Status diperbarui secara berurutan untuk menjaga histori jasa.
                                        </span>

                                        <div class="update-status-loading" wire:loading.flex wire:target="updateStatus">
                                            <x-filament::loading-indicator class="w-4 h-4" />
                                            Memperbarui status...
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="update-status-no-permission" style="display: flex; align-items: center; gap: 14px; border-radius: 8px; padding: 15px 24px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 32 32" width="38" height="38" style="flex-shrink:0;" stroke="#F59E42">
                                        <circle cx="16" cy="16" r="15" stroke="#d90606" stroke-width="2" fill="#FFF3DE"/>
                                        <path stroke="#d90606" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M16 11v6m0 4h.01"/>
                                    </svg>
                                    <div style="font-size: 13px; color: #d90606; text-align:left;">
                                        Saat ini Anda belum dapat melanjutkan ke status berikutnya.<br>
                                        <span style="font-size:13.5px; color:#ff0800;">
                                            Mohon tunggu hingga <b> <strong>{{ $nextSequentialStatusRoleDisplay }}</strong></b> menyelesaikan tugasnya terlebih dahulu.
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    {{-- End: Tombol Update --}}
                </div>
            </div>
        </div>
    </div>

        <!-- Real-time indicator -->
        <div class="realtime-info" >
            <div class="pulse-dot"></div>
            <span>Online</span>
        </div>
    @endif

    {{-- Image Modal/Lightbox --}}
    <div id="imageModal" class="image-modal" onclick="closeImageModal(event)">
        <div class="image-modal-content" onclick="event.stopPropagation()">
            <button class="image-modal-close" onclick="closeImageModal()" title="Tutup (Esc)">&times;</button>
            
            {{-- Navigation Arrows --}}
            <button class="image-modal-nav image-modal-prev" onclick="navigateImage(-1)" title="Foto Sebelumnya (←)">&#8249;</button>
            <button class="image-modal-nav image-modal-next" onclick="navigateImage(1)" title="Foto Selanjutnya (→)">&#8250;</button>
            
            <img id="modalImage" src="" alt="Progress Image Closeup">
            
            <div class="image-modal-info">
                <div id="modalCounter" class="image-modal-counter"></div>
                <div id="modalStatus" class="image-modal-status"></div>
                <div id="modalDate" class="image-modal-date"></div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

{{-- Global JavaScript for Image Modal - Must run before DOM interactions --}}
<script>
    // Image Modal with Navigation - Make functions globally accessible
    window.currentImageIndex = 0;
    window.allImages = [];

    window.openImageModal = function(imageSrc, statusInfo, dateInfo, index = 0) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const counterDiv = document.getElementById('modalCounter');
        const statusDiv = document.getElementById('modalStatus');
        const dateDiv = document.getElementById('modalDate');
        const prevBtn = document.querySelector('.image-modal-prev');
        const nextBtn = document.querySelector('.image-modal-next');
        
        // Collect all image data
        window.allImages = [];
        const imageItems = document.querySelectorAll('.progress-image-item[onclick]');
        imageItems.forEach((item, idx) => {
            const onclickAttr = item.getAttribute('onclick');
            const match = onclickAttr.match(/openImageModal\('([^']+)',\s*'([^']+)',\s*'([^']+)'(?:,\s*(\d+))?\)/);
            if (match) {
                window.allImages.push({
                    src: match[1],
                    status: match[2],
                    date: match[3],
                    index: idx
                });
            }
        });
        
        window.currentImageIndex = index;
        window.updateModalImage();
        
        // Show/hide navigation buttons based on image count
        if (window.allImages.length <= 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        }
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    window.updateModalImage = function() {
        const modalImg = document.getElementById('modalImage');
        const counterDiv = document.getElementById('modalCounter');
        const statusDiv = document.getElementById('modalStatus');
        const dateDiv = document.getElementById('modalDate');
        
        if (window.allImages[window.currentImageIndex]) {
            const img = window.allImages[window.currentImageIndex];
            modalImg.src = img.src;
            counterDiv.textContent = `Foto ${window.currentImageIndex + 1} dari ${window.allImages.length}`;
            statusDiv.textContent = `Status: ${img.status}`;
            dateDiv.textContent = `Diupload: ${img.date}`;
        }
    }

    window.navigateImage = function(direction) {
        if (window.allImages.length <= 1) return;
        
        window.currentImageIndex += direction;
        
        // Loop around
        if (window.currentImageIndex < 0) {
            window.currentImageIndex = window.allImages.length - 1;
        } else if (window.currentImageIndex >= window.allImages.length) {
            window.currentImageIndex = 0;
        }
        
        window.updateModalImage();
    }

    window.closeImageModal = function(event) {
        if (event && event.target !== event.currentTarget) return;
        
        const modal = document.getElementById('imageModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeImageModal();
        }
    });

    // Listen for Livewire events to clear file upload
    document.addEventListener('livewire:init', () => {
        Livewire.on('$refresh', () => {
            setTimeout(() => window.clearFileUpload && window.clearFileUpload(), 100);
        });
    });

    // Track file upload status
    window.trackUploadStatus = function() {
        const component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
        if (!component) return;

        const fileInputs = document.querySelectorAll('input[type="file"]');
        let isUploading = false;

        fileInputs.forEach(input => {
            // Check if file input has files being processed
            if (input.files && input.files.length > 0) {
                isUploading = true;
            }

            // Listen to file selection changes
            input.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    component.call('setUploadingStatus', true);
                } else {
                    component.call('setUploadingStatus', false);
                }
            });
        });

        // Listen for upload completion events from Filament
        document.addEventListener('filament:file-upload:completed', () => {
            setTimeout(() => {
                const hasFiles = Array.from(fileInputs).some(input => input.files && input.files.length > 0);
                component.call('setUploadingStatus', hasFiles);
            }, 500);
        });

        document.addEventListener('filament:file-upload:deleted', () => {
            setTimeout(() => {
                const hasFiles = Array.from(fileInputs).some(input => input.files && input.files.length > 0);
                component.call('setUploadingStatus', hasFiles);
            }, 300);
        });
    }

    // Initialize upload tracking
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => window.trackUploadStatus(), 500);
        });
    } else {
        setTimeout(() => window.trackUploadStatus(), 500);
    }

    // Re-track after Livewire updates
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', (el) => {
            if (el.el.querySelector && el.el.querySelector('input[type="file"]')) {
                setTimeout(() => window.trackUploadStatus(), 300);
            }
        });
    });

    window.clearFileUpload = function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach((input) => {
            input.value = '';
        });
        
        const previewContainers = document.querySelectorAll('[data-file-upload-item]');
        previewContainers.forEach((container) => {
            container.remove();
        });
    }

    // Set favicon
    (function() {
        const link = document.createElement('link');
        link.rel = 'icon';
        link.type = 'image/x-icon';
        link.href = window.location.origin + '/favicon.ico';
        const existingLink = document.querySelector('link[rel="icon"]');
        if (existingLink) {
            existingLink.remove();
        }
        document.head.appendChild(link);
    }());

    const registerLivewireErrorHandler = () => {
        if (! window.Livewire) {
            return;
        }

        Livewire.onError((statusCode) => {
            if (statusCode === 419) {
                window.location.reload();

                return false;
            }
        });
    };

    document.addEventListener('livewire:initialized', registerLivewireErrorHandler);
    document.addEventListener('livewire:init', registerLivewireErrorHandler);

    // Force black color for labels in terjadwal form
    const forceBlackLabels = () => {
        const wrappers = document.querySelectorAll('.terjadwal-form-wrapper, .terjadwal-form-container');
        wrappers.forEach(wrapper => {
            // Get all labels within wrapper
            const allLabels = wrapper.querySelectorAll('label, [class*="label"], [class*="Label"]');
            allLabels.forEach(label => {
                const labelText = (label.textContent || label.innerText || '').trim();
                // Check if this is one of our target labels
                if (labelText.includes('Jadwal Petugas') || labelText.includes('Pilih Petugas')) {
                    label.style.color = '#000000';
                    label.style.setProperty('color', '#000000', 'important');
                    // Force on all child elements too
                    const children = label.querySelectorAll('*');
                    children.forEach(child => {
                        child.style.setProperty('color', '#000000', 'important');
                    });
                }
            });
        });
    };

    // Run immediately and set up observers
    const initLabelFix = () => {
        forceBlackLabels();
        // Retry a few times to catch dynamic content
        setTimeout(forceBlackLabels, 100);
        setTimeout(forceBlackLabels, 300);
        setTimeout(forceBlackLabels, 500);
    };

    // Use MutationObserver to watch for DOM changes
    const observer = new MutationObserver(() => {
        forceBlackLabels();
    });

    // Start observing when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initLabelFix();
            const wrappers = document.querySelectorAll('.terjadwal-form-wrapper, .terjadwal-form-container');
            wrappers.forEach(wrapper => {
                observer.observe(wrapper, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['class', 'style']
                });
            });
        });
    } else {
        initLabelFix();
        const wrappers = document.querySelectorAll('.terjadwal-form-wrapper, .terjadwal-form-container');
        wrappers.forEach(wrapper => {
            observer.observe(wrapper, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class', 'style']
            });
        });
    }

    // Also run on Livewire events
    document.addEventListener('livewire:initialized', initLabelFix);
    if (window.Livewire) {
        Livewire.hook('morph.updated', () => {
            setTimeout(forceBlackLabels, 50);
            setTimeout(forceBlackLabels, 200);
        });
    }
</script>
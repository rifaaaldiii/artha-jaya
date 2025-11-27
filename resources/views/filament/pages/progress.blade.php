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
        @keyframes pulse {
          0% { box-shadow: 0 0 0 0 #22c55e70;}
          70% { box-shadow: 0 0 0 6px #22c55e10;}
          100% { box-shadow: 0 0 0 0 #22c55e00;}
        }
        .whitespace-pre-wrap { white-space: pre-wrap; }

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

    <!-- Pilih Produksi -->
    <div class="mb-6">
        <div class="max-w-md">
            {{ $this->produksiForm }}
        </div>
    </div>

    @if(!$this->record)
        <div class="detail-card">
            <div class="detail-header" style="background: transparent;">
                <div>
                    <div class="detail-header-title">Pilih Produksi</div>
                    <div style="font-size:1rem; color:#adb5bd;">Silakan pilih produksi untuk melihat progress.</div>
                </div>
            </div>
        </div>
    @else
        @php
            $statuses = [
                'produksi baru' => [
                    'label' => 'Produksi Baru',
                    'subtitle' => 'Pesanan baru masuk',
                    'step' => 1,
                ],
                'siap produksi' => [
                    'label' => 'Siap Produksi',
                    'subtitle' => 'Siap untuk diproses',
                    'step' => 2,
                ],
                'dalam pengerjaan' => [
                    'label' => 'Dalam Pengerjaan',
                    'subtitle' => 'Sedang dikerjakan',
                    'step' => 3,
                ],
                'selesai dikerjakan' => [
                    'label' => 'Selesai Dikerjakan',
                    'subtitle' => 'Pengerjaan selesai',
                    'step' => 4,
                ],
                'lolos qc' => [
                    'label' => 'Lolos QC',
                    'subtitle' => 'Quality control passed',
                    'step' => 5,
                ],
                'produksi siap diambil' => [
                    'label' => 'Siap Diambil',
                    'subtitle' => 'Siap untuk diambil',
                    'step' => 6,
                ],
                'selesai' => [
                    'label' => 'Selesai',
                    'subtitle' => 'Produksi selesai',
                    'step' => 7,
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
                                @if($step === 7 && $selesaiStepIsCheck)
                                    <!-- Special: Step 7 is checklist if status selesai -->
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
                                @if($isCurrent) current @elseif($isCompleted || ($step === 7 && $selesaiStepIsCheck)) completed @else upcoming @endif">
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
        <div style="flex:1;">
            <div class="detail-card">
                <div class="detail-header">
                    <div style="flex:2;">
                        <div class="detail-header-title">
                            Detail Produksi
                        </div>
                        <div class="detail-header-status">
                            @php
                                // Sesuaikan badge untuk admin gudang: status 1 (siap produksi) hijau, status 2 (siap diambil) biru, selain itu default
                                if(auth()->user() && auth()->user()->role === 'admin gudang') {
                                    $badgeClass =
                                        $currentStep === 1 ? 'status-badge status-green' :
                                        ($currentStep === 2 ? 'status-badge status-blue' : 'status-badge status-bg-default');
                                } else {
                                    $badgeClass =
                                        $currentStep === 1 ? 'status-badge status-red' :
                                        ($currentStep === 2 ? 'status-badge status-blue' :
                                        ($currentStep === 3 ? 'status-badge status-yellow' :
                                        ($currentStep === 4 ? 'status-badge status-green' :
                                        ($currentStep === 5 ? 'status-badge status-indigo' :
                                        ($currentStep === 6 ? 'status-badge status-purple' : 'status-badge status-bg-default'
                                        )))));
                                }
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $statuses[$currentStatus]['label'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="detail-main">
                    <ul class="detail-list">
                        <li class="detail-list-item">
                            <span class="detail-item-label">No. Produksi</span>
                            <span class="detail-item-value">{{ $this->record->no_produksi }}</span>
                        </li>
                        <li class="detail-list-item">
                            <span class="detail-item-label">Jenis Produksi</span>
                            <span class="detail-item-value">{{ $this->record->nama_produksi . ' - ' . $this->record->nama_bahan }}</span>
                        </li>
                        <li class="detail-list-item">
                            <span class="detail-item-label">Jumlah</span>
                            <span class="detail-item-value">{{ number_format($this->record->jumlah, 0, ',', '.') }} Unit</span>
                        </li>
                        @if($this->record->team)
                        <li class="detail-list-item">
                            <span class="detail-item-label">Team</span>
                            <span class="detail-item-value">{{ $this->record->team->nama }}</span>
                        </li>
                        @endif
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
                    @if($this->record->catatan)
                    <div>
                        <span class="detail-item-label" style="color:#e11d48;">Catatan</span>
                        <div class="detail-note whitespace-pre-wrap">
                            {{ $this->record->catatan }}
                        </div>
                    </div>
                    @endif

                    {{-- Tombol dan Dropdown untuk Update Status --}}
                    @php
                        $allowedStatuses = $this->allowedStatuses ?? array_keys($statuses);
                        $nextSequentialStatus = $this->nextSequentialStatus ?? null;
                        $canProceedNext = $nextSequentialStatus && in_array($nextSequentialStatus, $allowedStatuses, true);
                        // Always enable: no validation
                        $isUpdateEnabled = true;

                        // Find the role allowed for the next status - for info message if not allowed
                        $nextSequentialStatusRole = null;
                        if ($nextSequentialStatus) {
                            // Mapping from Progress.php
                            $roleStatusMap = [
                                'admin_toko' => ['produksi baru', 'selesai'],
                                'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
                                'kepala_teknisi_gudang' => ['dalam pengerjaan', 'lolos qc'],
                                'petukang' => ['selesai dikerjakan'],
                            ];
                            foreach ($roleStatusMap as $role => $statusesForRole) {
                                if (in_array($nextSequentialStatus, $statusesForRole, true)) {
                                    $nextSequentialStatusRole = $role;
                                    break;
                                }
                            }
                            // fallback if not found, for admin etc
                            if (!$nextSequentialStatusRole) {
                                $nextSequentialStatusRole = 'administrator';
                            }
                            // Format role for display
                            $nextSequentialStatusRoleDisplay = ucwords(str_replace('_', ' ', $nextSequentialStatusRole));
                        } else {
                            $nextSequentialStatusRoleDisplay = '-';
                        }
                    @endphp

                    @if($this->record->status !== 'selesai')
                        <div class="update-status-card">
                            <div class="update-status-header">
                                <div>
                                    <div class="update-status-title">Update Status Produksi</div>
                                    <p class="update-status-subtitle">
                                        Status hanya dapat bergerak ke langkah berikutnya.
                                    </p>
                                </div>
                            </div>

                            @if($canProceedNext)
                                <div class="update-status-body">
                                    <div class="update-status-field">
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

                                            <x-filament::button
                                                color="success"
                                                icon="heroicon-m-check-badge"
                                                wire:click="updateStatus"
                                                wire:loading.attr="disabled"
                                                wire:target="updateStatus"
                                                :disabled="false"
                                            >
                                                Simpan Status
                                            </x-filament::button>
                                        </div>
                                    </div>
                                    <div class="update-status-meta">
                                        <span class="update-status-helper">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                            Status diperbarui secara berurutan untuk menjaga histori produksi.
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
        <div class="realtime-info">
            <div class="pulse-dot"></div>
            <span>Online</span>
        </div>
    @endif
</x-filament-panels::page>

@script
    // Set favicon
    (function() {
        const link = document.createElement('link');
        link.rel = 'icon';
        link.type = 'image/x-icon';
        link.href = '{{ asset("favicon.ico") }}';
        const existingLink = document.querySelector('link[rel="icon"]');
        if (existingLink) {
            existingLink.remove();
        }
        document.head.appendChild(link);
    })();

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
@endscript
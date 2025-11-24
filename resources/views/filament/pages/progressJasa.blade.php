<x-filament-panels::page wire:poll.3s="refresh">
    <style>
        .mb-6 { margin-bottom: 24px; }
        .max-w-md { max-width: 440px; }
        .block { display: block; }
        .role-action-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 6px;
            font-size: 12px;
            color: #f97316;
            font-weight: 600;
        }
        .role-action-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #fb923c;
            animation: pulse 2s infinite;
            box-shadow: 0 0 0 0 #fb923c55;
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
        .prog-step-circle { background: #14b8a6; }
        .prog-step-circle svg { width: 24px; height: 24px; color: #fff; }
        .prog-step-circle-current {
            background: #0694a2; box-shadow: 0 0 0 4px #99f6e4; color: #fff; font-weight: bold;
        }
        .prog-step-circle-upcoming {
            background: #d1d5db; color: #6b7280; font-weight: bold;
        }
        .prog-connector {
            width: 3px; height: 64px; margin-top: 10px;
            background: #d1d5db;
        }
        .prog-connector.completed { background: #14b8a6; }
        .prog-step-label {
            font-weight: bold; font-size: 16px;
        }
        .prog-step-label.current { color: #0694a2; }
        .prog-step-label.completed { color: #14b8a6; }
        .prog-step-label.upcoming { color: #6b7280; }
        .prog-step-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .detail-card {
            background: #fff;
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        .detail-header {
            background: transparent;
            color: #22223b;
            padding: 22px 24px 12px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-header-icon {
            width: 44px; height: 44px;
            background: #f1f5f9;
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
            color: #262626;
        }
        .detail-list-item:last-child { border-bottom: none; }
        .detail-item-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            margin-right: 18px;
            flex-shrink: 0;
        }
        .detail-item-value {
            color: #22223b;
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
            color: #dc2626;
            font-size: 13.2px;
            font-style: italic;
            word-break: break-word;
            margin-top: 1px;
        }
        .realtime-info {
            display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280; margin-top: 16px;
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
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: linear-gradient(135deg, #f0fdfa, #ecfeff);
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
            color: #0f172a;
        }
        .update-status-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #475569;
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
            color: #0f172a;
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
            border: 1px solid #cbd5f5;
            background: #fff;
            color: #111827;
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
            color: #0f172a;
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
        <div style="flex:1;">
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
                            <span class="detail-item-label">Jenis Layanan</span>
                            <span class="detail-item-value">{{ $this->record->jenis_layanan }}</span>
                        </li>
                        @if($this->record->jadwal)
                        <li class="detail-list-item">
                            <span class="detail-item-label">Jadwal</span>
                            <span class="detail-item-value">{{ $this->record->jadwal->format('d F Y, H:i') }} WIB</span>
                        </li>
                        @endif
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
                        @if($this->record->jadwal_petugas)
                        <li class="detail-list-item">
                            <span class="detail-item-label">Jadwal Petugas</span>
                            <span class="detail-item-value">{{ $this->record->jadwal_petugas->format('d F Y, H:i') }} WIB</span>
                        </li>
                        @endif
                        @if($this->record->pelanggan)
                        <li class="detail-list-item">
                            <span class="detail-item-label">Pelanggan</span>
                            <span class="detail-item-value">{{ $this->record->pelanggan->nama }}</span>
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
                        $isUpdateDisabled = ! $canProceedNext || $this->updateStatusValue !== $nextSequentialStatus;
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
                                                >
                                                    Simpan Status & Jadwalkan Petugas
                                                </x-filament::button>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Form normal untuk status lainnya --}}
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
                                                    :disabled="$isUpdateDisabled"
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
                                <div class="update-status-body">
                                    <div class="update-status-field">
                                        <div class="update-status-helper" style="font-size:14px;">
                                            Role Anda belum memiliki izin untuk melanjutkan ke status berikutnya. Silakan hubungi role terkait agar proses tetap berurutan.
                                        </div>
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
            <span>Refresh Every 3 Seconds</span>
        </div>
    @endif

    @script
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
    @endscript
</x-filament-panels::page>

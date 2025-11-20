<x-filament-panels::page wire:poll.1s="refresh">
    <style>
        .mb-6 { margin-bottom: 24px; }
        .max-w-md { max-width: 440px; }
        .block { display: block; }
        .input-produk {
            width: 100%; border-radius: 8px; border: 1px solid #d1d5db;
            background: #fff; color: #111827; box-shadow: 0 1px 2px rgba(0,0,0,.02);
            padding: 8px 12px; font-size: 15px;
        }
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
    </style>

    <!-- Pilih Produksi -->

    <div class="mb-6">
        <div class="max-w-md">
            <label for="selectedProduksiId" class="block" style="font-size:14px;font-weight:500;color:#374151;margin-bottom:9px; padding-right: 10px;">
                Pilih Produksi
            </label>
            <select 
                id="selectedProduksiId"
                wire:model.live="selectedProduksiId" 
                class="input-produk"
            >
                <option value="">-- Pilih Produksi --</option>
                @foreach($this->getProduksiOptions() as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach
            </select>
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
            if(auth()->user() && auth()->user()->role === 'admin gudang') {
                $statuses = [
                    'siap produksi' => [
                        'label' => 'Siap Produksi',
                        'subtitle' => 'Siap untuk diproses',
                        'step' => 1,
                    ],
                    'produksi siap diambil' => [
                        'label' => 'Siap Diambil',
                        'subtitle' => 'Siap untuk diambil',
                        'step' => 2,
                    ],
                ];

                $currentStatus = $this->record->status;
                if ($currentStatus === 'siap produksi') {
                    $currentStep = 1;
                } elseif ($currentStatus === 'produksi siap diambil') {
                    $currentStep = 2;
                } else {
                    $currentStep = 1;
                    $currentStatus = 'siap produksi';
                }
            } else {
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
            }
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
                            <span class="detail-item-value">{{ $this->record->createdAt ? $this->record->createdAt->format('d F Y, H:i') : '-' }}</span>
                        </li>
                        @if($this->record->updateAt)
                        <li class="detail-list-item">
                            <span class="detail-item-label">Terakhir Diupdate</span>
                            <span class="detail-item-value">{{ $this->record->updateAt->format('d F Y, H:i') }}</span>
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
</x-filament-panels::page>
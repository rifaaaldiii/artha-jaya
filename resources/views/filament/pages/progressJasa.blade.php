<x-filament-panels::page wire:poll.3s="refresh">
    <style>
        .mb-6 { margin-bottom: 24px; }
        .max-w-md { max-width: 440px; }
        .block { display: block; }
        .input-jasa {
            width: 100%; border-radius: 8px; border: 1px solid #d1d5db;
            background: #fff; color: #111827; box-shadow: 0 1px 2px rgba(0,0,0,.02);
            padding: 8px 12px; font-size: 15px;
        }
        .progress-container { display: flex; gap: 32px; }
        .prog-sidebar { width: 240px; flex-shrink: 0; }
        .prog-steps { display: flex; flex-direction: column; gap: 8px;}
        .prog-step-row { display: flex; align-items: flex-start; gap: 16px; }
        .prog-step-circles-col { display: flex; flex-direction: column; align-items: center;}
        .prog-step-circle,
        .prog-step-circle-current,
        .prog-step-circle-upcoming {
            width: 35px; height: 35px; border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
        }
        .prog-step-circle { background: #0ea5e9; }
        .prog-step-circle svg { width: 24px; height: 24px; color: #fff; }
        .prog-step-circle-current {
            background: #0284c7; box-shadow: 0 0 0 4px #bae6fd; color: #fff; font-weight: bold;
        }
        .prog-step-circle-upcoming {
            background: #e5e7eb; color: #6b7280; font-weight: bold;
        }
        .prog-connector {
            width: 3px; height: 58px; margin-top: 10px; background: #e5e7eb;
        }
        .prog-connector.completed { background: #0ea5e9; }
        .prog-step-label { font-weight: 600; font-size: 15px; }
        .prog-step-label.current { color: #0369a1; }
        .prog-step-label.completed { color: #0ea5e9; }
        .prog-step-label.upcoming { color: #9ca3af; }
        .prog-step-subtitle { font-size: 13px; color: #6b7280; margin-top: 4px; }

        .detail-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        .detail-header {
            padding: 22px 24px 12px 24px;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-header-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 6px; color: #1f2937; }
        .detail-main { padding: 18px 24px 22px 24px; display: flex; flex-direction: column; gap: 12px; }
        .detail-list { list-style: none; padding: 0; margin: 0; }
        .detail-list-item {
            display: flex; justify-content: space-between; gap: 18px;
            padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 15px;
        }
        .detail-list-item:last-child { border-bottom: none; }
        .detail-item-label { color: #6b7280; font-size: 14px; font-weight: 500; width: 40%; }
        .detail-item-value { color: #111827; font-size: 15px; font-weight: 600; flex: 1; text-align: right; }
        .status-badge { display: inline-flex; align-items: center; padding: 3px 14px; border-radius: 999px; font-size: 13px; font-weight: 600;}
        .status-info { background: #dbeafe; color: #1d4ed8; }
        .status-warning { background: #fef3c7; color: #b45309; }
        .status-success { background: #dcfce7; color: #166534; }
        .status-neutral { background: #e5e7eb; color: #374151; }
        .detail-note { color: #9f1239; font-size: 13px; font-style: italic; margin-top: 2px; text-align: right; }
        .realtime-info { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6b7280; margin-top: 16px; }
        .pulse-dot {
            width: 9px; height: 9px; background: #22c55e; border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
          0% { box-shadow: 0 0 0 0 #22c55e70;}
          70% { box-shadow: 0 0 0 6px #22c55e10;}
          100% { box-shadow: 0 0 0 0 #22c55e00;}
        }

        @media (max-width: 1024px) {
            .progress-container { gap: 16px; }
            .prog-sidebar { width: 220px; }
        }
        @media (max-width: 768px) {
            .progress-container { flex-direction: column; }
            .prog-sidebar { width: 100%; }
            .detail-list-item { flex-direction: column; align-items: flex-start; }
            .detail-item-value { text-align: left; }
        }
    </style>

    <div class="mb-6">
        <div class="max-w-md">
            <label for="selectedJasaId" class="block" style="font-size:14px;font-weight:600;color:#374151;margin-bottom:9px;">
                Pilih Jasa / Layanan
            </label>
            <select
                id="selectedJasaId"
                wire:model.live="selectedJasaId"
                class="input-jasa"
            >
                <option value="">-- Pilih Layanan --</option>
                @foreach($this->getJasaOptions() as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if(!$this->record)
        <div class="detail-card">
            <div class="detail-header">
                <div class="detail-header-title">Belum ada data</div>
                <p style="color:#9ca3af;font-size:15px;margin:0;">
                    Silakan pilih jasa / layanan untuk melihat progress detailnya.
                </p>
            </div>
        </div>
    @else
        @php
            $statuses = [
                'jasa baru' => [
                    'label' => 'Permintaan Baru',
                    'subtitle' => 'Order diterima dan menunggu penjadwalan',
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
        @endphp

    <div class="progress-container">
        <div class="prog-sidebar">
            <div class="prog-steps">
                @foreach($statuses as $statusKey => $statusInfo)
                    @php
                        $step = $statusInfo['step'];
                        $isCompleted = $currentStep > $step || ($statusKey === 'selesai' && $this->record->status === 'selesai');
                        $isCurrent = $currentStep === $step;
                    @endphp

                    <div class="prog-step-row">
                        <div class="prog-step-circles-col">
                            <div>
                                @if($isCompleted && $statusKey === 'selesai')
                                    <div class="prog-step-circle">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @elseif($isCompleted)
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
                            </div>
                            <div class="prog-step-subtitle">
                                {{ $statusInfo['subtitle'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="flex:1;">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-header-title">
                        Detail Layanan
                    </div>
                    @php
                        $badgeClass = match ($currentStatus) {
                            'jasa baru' => 'status-badge status-info',
                            'terjadwal' => 'status-badge status-warning',
                            'selesai dikerjakan' => 'status-badge status-neutral',
                            default => 'status-badge status-success',
                        };
                    @endphp
                    <span class="{{ $badgeClass }}">{{ $statuses[$currentStatus]['label'] ?? ucfirst($currentStatus) }}</span>
                </div>
                <div class="detail-main">
                    <ul class="detail-list">
                        <li class="detail-list-item">
                            <span class="detail-item-label">No. Referensi</span>
                            <span class="detail-item-value">{{ $this->record->no_ref }}</span>
                        </li>
                        <li class="detail-list-item">
                            <span class="detail-item-label">Jenis Layanan</span>
                            <span class="detail-item-value">{{ $this->record->jenis_layanan }}</span>
                        </li>
                        <li class="detail-list-item">
                            <span class="detail-item-label">Jadwal</span>
                            <span class="detail-item-value">
                                {{ $this->record->jadwal ? $this->record->jadwal->format('d F Y, H:i') : '-' }}
                            </span>
                        </li>
                        @if($this->record->petugas)
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
                        <li class="detail-list-item">
                            <span class="detail-item-label">Tanggal Dibuat</span>
                            <span class="detail-item-value">
                                {{ $this->record->createdAt ? $this->record->createdAt->format('d F Y, H:i') : '-' }}
                            </span>
                        </li>
                        @if($this->record->updateAt)
                        <li class="detail-list-item">
                            <span class="detail-item-label">Terakhir Diupdate</span>
                            <span class="detail-item-value">{{ $this->record->updateAt->format('d F Y, H:i') }}</span>
                        </li>
                        @endif
                    </ul>
                    @if($this->record->catatan)
                        <div class="detail-note whitespace-pre-wrap">
                            {{ $this->record->catatan }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

        <div class="realtime-info">
            <div class="pulse-dot"></div>
            <span>Pembaruan otomatis setiap 3 detik</span>
        </div>
    @endif
</x-filament-panels::page>
</x-filament-panels::page>
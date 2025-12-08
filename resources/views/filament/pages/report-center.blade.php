<x-filament-panels::page>
    <style>
        /* Container spacing */
        .rc-space-y-6 > * + * { margin-top: 2rem; }

        /* Card box */
        .rc-card {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 24px;
            box-shadow: 0 1px 4px 0 rgba(16, 24, 40, 0.05);
            margin-bottom: 0;
        }

        .rc-shadow { box-shadow: 0 1px 4px 0 rgba(16, 24, 40, 0.05); }
        .rc-rounded { border-radius: 16px; }

        /* Flex helpers */
        .rc-flex { display: flex; }
        .rc-flex-col { flex-direction: column; }
        .rc-flex-row { flex-direction: row; }
        .rc-flex-wrap { flex-wrap: wrap; }
        .rc-items-center { align-items: center; }
        .rc-justify-between { justify-content: space-between; }
        .rc-gap-4 { gap: 1rem; }
        .rc-gap-2 { gap: 0.5rem; }
        .rc-gap-3 { gap: 0.75rem; }

        /* Typography */
        .rc-title { font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem; }
        .rc-subtitle { font-size: 0.875rem; color: #6b7280; margin-bottom: 0; }
        .rc-label { font-size: 0.875rem; color: #6b7280; }
        .rc-h3 { font-size: 1.125rem; font-weight: 600; color: #111827; }
        .rc-data-label { font-size: 0.875rem; color: #6b7280; }
        .rc-highlight { font-size: 2rem; font-weight: 600; color: #111827; }
        .rc-success { color: #059669; }
        .rc-warning { color: #d97706; }
        .rc-xs { font-size: 0.75rem; }
        .rc-mt2 { margin-top: 0.5rem;}
        .rc-mt1 { margin-top: 0.25rem;}
        .rc-mt4 { margin-top: 1rem;}
        .rc-mt8 { margin-top: 2rem;}

        /* Table styles */
        .rc-table-container { overflow-x: auto; margin-top: 1rem; }
        table.rc-table { min-width: 100%; border-collapse: collapse; font-size: 0.9375rem; }
        .rc-table th, .rc-table td { padding: 0.75rem 0.75rem; border-bottom: 1px solid #f3f4f6; }
        .rc-table th { text-align: left; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; font-size: 0.8125rem; color: #6b7280; background: #fafbfc;}
        .rc-table tbody tr { color: #111827; background: #fff; }
        .rc-table .rc-note { margin-top: 0.25rem; color: #6b7280; font-size: 0.75rem; }
        .rc-table .rc-badge { display: inline-block; border-radius: 999px; background: #f3f4f6; color: #374151; font-size: 0.75rem; font-weight: 600; padding: 2px 9px; }
        .rc-table .rc-small-text { font-size: 0.75rem; color: #6b7280;}

        @media (min-width: 1024px) {
            .rc-preview-grid { 
                grid-template-columns: 2fr 1fr;
            }
        }

        /* Status breakdown */
        .rc-status-list > * + * { margin-top: 0.75rem; }
        .rc-status-card {
            border: 1px solid #f3f4f6;
            border-radius: 12px;
            padding: 1rem 1.5rem 1rem 1.5rem;
            background: #fff;
        }
        .rc-status-card .rc-row { display: flex; align-items: center; justify-content: space-between; font-size: 0.9375rem;}
        .rc-status-card .rc-bar-bg {
            width: 100%;
            background: #f3f4f6;
            height: 8px;
            border-radius: 8px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .rc-status-card .rc-bar {
            background: #22c55e;
            height: 8px;
            border-radius: 8px;
        }
        .rc-empty {
            border: 1px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            font-size: 0.9375rem;
            color: #6b7280;
        }

        /* Buttons - just spacing */
        .rc-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* Misc spacing utilities */
        .rc-p-4 { padding: 1rem; }
        .rc-p-5 { padding: 1.25rem; }
        .rc-mt-2 { margin-top: 0.5rem; }
        .rc-mt-6 { margin-top: 1.5rem;}
    </style>
    @php
        $reportType = $this->filters['report_type'] ?? 'jasa';
        $previewIsEmpty = empty($this->previewRows);
        $maxPreview = \App\Filament\Pages\ReportCenter::MAX_PREVIEW_ROWS;
    @endphp

    <div class="rc-space-y-6">
        <!-- Filters -->
        <div class="rc-card">
            <div class="rc-flex rc-flex-col rc-gap-4" style="gap:1rem;">
                <div>
                    <h2 class="rc-title">Pengaturan Filter</h2>
                    <p class="rc-subtitle">
                        Gunakan filter di bawah untuk menentukan data yang akan ditampilkan serta dimasukkan ke PDF.
                    </p>
                </div>
                <div class="rc-btns">
                    <x-filament::button color="gray" wire:click="resetFilters" icon="heroicon-m-arrow-path">
                        Reset
                    </x-filament::button>
                    <x-filament::button color="primary" wire:click="applyFilters" icon="heroicon-m-funnel">
                        Terapkan Filter
                    </x-filament::button>
                </div>
            </div>
            <div class="rc-mt-6">
                {{ $this->filterForm }}
            </div>
        </div>

        <div class="rc-preview-grid">
            <div class="rc-card rc-p-5" 
                style="grid-column:span 2;">
                <div class="rc-flex rc-flex-col rc-gap-3" style="gap:0.75rem;">
                    <div>
                        <h3 class="rc-h3">
                            Preview Data ({{ count($this->previewRows) }} dari {{ $this->resultCount }} data)
                        </h3>
                        <p class="rc-label">
                            Hanya {{ $maxPreview }} data terbaru yang ditampilkan sebagai preview.
                        </p>
                    </div>
                    <div class="rc-btns">
                        <x-filament::button
                            color="success"
                            icon="heroicon-m-arrow-down-tray"
                            wire:click="downloadPdfByDateRange"
                            wire:loading.attr="disabled"
                            wire:target="downloadPdfByDateRange"
                            :disabled="$this->resultCount === 0"
                        >
                            Download PDF (Rentang Waktu)
                        </x-filament::button>
                        <x-filament::button
                            color="primary"
                            icon="heroicon-m-document-arrow-down"
                            wire:click="toggleSingleDownloadForm"
                        >
                            Download PDF (Single Data)
                        </x-filament::button>
                    </div>
                    
                    @if($this->showSingleDownloadForm)
                    <!-- Single Download Form Section -->
                    <div class="rc-card rc-mt-6">
                        <div class="rc-flex rc-flex-col rc-gap-4">
                            <div>
                                <h3 class="rc-h3">Download PDF - Single Data</h3>
                                <p class="rc-label">
                                    Masukkan nomor {{ $reportType === 'produksi' ? 'Produksi' : 'Jasa' }} untuk mengunduh data tunggal. Hanya data dengan status "Selesai" yang dapat diunduh.
                                </p>
                            </div>
                            <div>
                                {{ $this->singleDownloadForm }}
                            </div>
                            <div class="rc-btns">
                                <x-filament::button
                                    color="success"
                                    icon="heroicon-m-arrow-down-tray"
                                    wire:click="downloadPdfSingle"
                                    wire:loading.attr="disabled"
                                    wire:target="downloadPdfSingle"
                                >
                                    Download PDF
                                </x-filament::button>
                                <x-filament::button
                                    color="gray"
                                    wire:click="toggleSingleDownloadForm"
                                >
                                    Batal
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="rc-table-container">
                    @if($previewIsEmpty)
                        <div class="rc-empty">
                            Belum ada data untuk ditampilkan. Silakan sesuaikan filter terlebih dahulu.
                        </div>
                    @else
                        <table class="rc-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if($reportType === 'produksi')
                                        <th>No Produksi</th>
                                        <th>Nama Produksi</th>
                                        <th>Bahan</th>
                                        <th>Jumlah</th>
                                        <th>Team</th>
                                        <th>Update</th>
                                    @else
                                        <th>No Jasa</th>
                                        <th>No Ref</th>
                                        <th>Layanan</th>
                                        <th>Pelanggan</th>
                                        <th>Petugas</th>
                                        <th>Jadwal</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($this->previewRows as $index => $row)
                                <tr>
                                    <td class="rc-small-text">
                                        {{ $index + 1 }}
                                    </td>
                                    @if($reportType === 'produksi')
                                        <td style="font-weight:600;">{{ $row['number'] ?? '-' }}</td>
                                        <td>
                                            <div style="font-weight:600;">{{ $row['name'] ?? '-' }}</div>
                                            @if(!empty($row['note']))
                                                <p class="rc-note">Catatan: {{ $row['note'] }}</p>
                                            @endif
                                        </td>
                                        <td>{{ $row['material'] ?? '-' }}</td>
                                        <td>{{ $row['quantity'] ?? '-' }}</td>
                                        <td>{{ $row['team'] ?? '-' }}</td>
                                        <td class="rc-small-text">{{ $row['updated_at'] ?? '-' }}</td>
                                    @else
                                        <td style="font-weight:600;">{{ $row['number'] }}</td>
                                        <td>{{ $row['reference'] ?? '-' }}</td>
                                        <td>
                                            <div style="font-weight:600;">{{ $row['service'] }}</div>
                                            @if(!empty($row['note']))
                                                <p class="rc-note">Catatan: {{ $row['note'] }}</p>
                                            @endif
                                        </td>
                                        <td>{{ $row['customer'] ?? '-' }}</td>
                                        <td>{{ $row['petugas'] ?? '-' }}</td>
                                        <td class="rc-small-text">{{ $row['scheduled_at'] ?? '-' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

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
        .rc-table-container { 
            overflow-x: auto; 
            margin-top: 1rem; 
            -webkit-overflow-scrolling: touch;
            position: relative;
        }
        .rc-table-container::-webkit-scrollbar {
            height: 8px;
        }
        .rc-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .rc-table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .rc-table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        table.rc-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.9375rem;
            min-width: 800px; /* Minimum width untuk desktop */
        }
        .rc-table th, .rc-table td { 
            padding: 0.75rem; 
            border-bottom: 1px solid #f3f4f6; 
        }
        .rc-table th, .rc-table td:not(.rc-wrap) { 
            white-space: nowrap;
        }
        .rc-table td.rc-wrap {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }
        .rc-table th { 
            text-align: left; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            font-weight: 600; 
            font-size: 0.8125rem; 
            color: #6b7280; 
            background: #fafbfc;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .rc-table tbody tr { 
            color: #111827; 
            background: #fff;
            transition: background-color 0.2s;
        }
        .rc-table tbody tr:hover {
            background: #f9fafb;
        }
        .rc-table .rc-note { 
            margin-top: 0.25rem; 
            color: #6b7280; 
            font-size: 0.75rem;
            white-space: normal;
            word-wrap: break-word;
        }
        .rc-table .rc-badge { 
            display: inline-block; 
            border-radius: 999px; 
            background: #f3f4f6; 
            color: #374151; 
            font-size: 0.75rem; 
            font-weight: 600; 
            padding: 2px 9px; 
        }
        .rc-table .rc-small-text { 
            font-size: 0.75rem; 
            color: #6b7280;
        }
        
        /* Responsive table for mobile */
        @media (max-width: 768px) {
            .rc-table-container {
                overflow-x: scroll;
                -webkit-overflow-scrolling: touch;
                position: relative;
            }
            table.rc-table {
                min-width: 1000px; /* Wider minimum for mobile scroll */
                font-size: 0.875rem;
            }
            .rc-table th, .rc-table td {
                padding: 0.5rem 0.75rem;
            }
            .rc-table th {
                font-size: 0.75rem;
            }
            .rc-table td.rc-wrap {
                max-width: 150px;
            }
        }
        
        @media (max-width: 480px) {
            table.rc-table {
                min-width: 1200px;
                font-size: 0.8125rem;
            }
            .rc-table th, .rc-table td {
                padding: 0.4rem 0.5rem;
            }
        }

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
        .rc-btns { 
            display: flex; 
            gap: 0.5rem; 
            flex-wrap: wrap;
        }

        .rc-btns-1 { 
            display: flex; 
            gap: 0.5rem; 
            flex-wrap: wrap; 
            justify-content: end;
            margin-top: 1rem;
        }

        /* Misc spacing utilities */
        .rc-p-4 { padding: 1rem; }
        .rc-p-5 { padding: 1.25rem; }
        .rc-mt-2 { margin-top: 0.5rem; }
        .rc-mt-6 { margin-top: 1.5rem;}

        /* Pagination */
        .rc-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            padding: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        .rc-pagination-info {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .rc-pagination-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .rc-pagination-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            color: #374151;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .rc-pagination-btn:hover:not(:disabled) {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        .rc-pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .rc-pagination-btn.active {
            background: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
        }
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
                    <h2 class="rc-title">Filter</h2>
                    <p class="rc-subtitle">
                        Gunakan filter di bawah untuk menentukan data yang akan ditampilkan serta didownload ke PDF.
                    </p>
                </div>
            </div>
            <div class="rc-mt-6">
                {{ $this->filterForm }}
            </div>
            <div class="rc-btns-1">
                <x-filament::button color="success" wire:click="resetFilters" icon="heroicon-m-arrow-path">
                    Reset
                </x-filament::button>
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
                            Menampilkan {{ (($this->currentPage - 1) * $this->perPage) + 1 }} - {{ min(($this->currentPage * $this->perPage), $this->resultCount) }} dari {{ $this->resultCount }} data.
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
                            Download PDF
                        </x-filament::button>
                    </div>
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
                                        <th>Action</th>
                                    @else
                                        <th>No Jasa</th>
                                        <th>No Ref</th>
                                        <th>Layanan</th>
                                        <th>Pelanggan</th>
                                        <th>Petugas</th>
                                        <th>Jadwal</th>
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($this->previewRows as $index => $row)
                                <tr>
                                    <td class="rc-small-text">
                                        {{ (($this->currentPage - 1) * $this->perPage) + $index + 1 }}
                                    </td>
                                    @if($reportType === 'produksi')
                                        <td style="font-weight:600;">{{ $row['number'] ?? '-' }}</td>
                                        <td class="rc-wrap">
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
                                        <td class="rc-wrap">
                                            <div style="font-weight:600;">{{ $row['service'] }}</div>
                                            @if(!empty($row['note']))
                                                <p class="rc-note">Catatan: {{ $row['note'] }}</p>
                                            @endif
                                        </td>
                                        <td>{{ $row['customer'] ?? '-' }}</td>
                                        <td class="rc-wrap">{{ $row['petugas'] ?? '-' }}</td>
                                        <td class="rc-small-text">{{ $row['scheduled_at'] ?? '-' }}</td>
                                    @endif
                                    <td style="white-space: nowrap;">
                                        <button
                                            type="button"
                                            wire:click="downloadPdfSingleByNumber('{{ $row['number'] }}')"
                                            @if(isset($this->downloadingNumbers[$row['number']])) disabled @endif
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                gap: 0.25rem;
                                                border-radius: 0.5rem;
                                                border: none;
                                                padding: 0.3rem 0.6rem;
                                                font-size: 0.82rem;
                                                font-weight: 600;
                                                color: #fff;
                                                background-color: #16a34a;
                                                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                                                transition: background 0.15s;
                                                cursor: pointer;
                                                outline: none;
                                                opacity: {{ isset($this->downloadingNumbers[$row['number']]) ? '0.7' : '1' }};
                                                pointer-events: {{ isset($this->downloadingNumbers[$row['number']]) ? 'none' : 'auto' }};
                                            "
                                            title="Download PDF untuk {{ $row['number'] }}"
                                            onmouseover="this.style.backgroundColor='#22c55e'"
                                            onmouseout="this.style.backgroundColor='{{ isset($this->downloadingNumbers[$row['number']]) ? '#16a34a' : '#16a34a' }}'"
                                        >
                                            <svg style="height: 1rem; width: 1rem; color: #fff; margin-right: 0.22rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            @if(!isset($this->downloadingNumbers[$row['number']]))
                                                <span>Download PDF</span>
                                            @else
                                                <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    <svg style="animation: spin 1s linear infinite; height: 1rem; width: 1rem; color: #fff;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                                                        <path style="opacity: 0.75;" fill="white" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
                                                <style>
                                                    @keyframes spin {
                                                        0% { transform: rotate(0deg);}
                                                        100% { transform: rotate(360deg);}
                                                    }
                                                </style>
                                            @endif
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($this->resultCount > $this->perPage)
                        <div class="rc-pagination">
                            <div class="rc-pagination-info">
                                Menampilkan {{ (($this->currentPage - 1) * $this->perPage) + 1 }} - {{ min(($this->currentPage * $this->perPage), $this->resultCount) }} dari {{ $this->resultCount }} data
                            </div>
                            <div class="rc-pagination-controls">
                                <button
                                    class="rc-pagination-btn"
                                    wire:click="previousPage"
                                    wire:loading.attr="disabled"
                                    @if($this->currentPage <= 1) disabled @endif
                                >
                                    Sebelumnya
                                </button>
                                @php
                                    $totalPages = max(1, (int) ceil($this->resultCount / $this->perPage));
                                    $startPage = max(1, $this->currentPage - 2);
                                    $endPage = min($totalPages, $this->currentPage + 2);
                                @endphp
                                
                                @if($startPage > 1)
                                    <button
                                        class="rc-pagination-btn"
                                        wire:click="goToPage(1)"
                                        wire:loading.attr="disabled"
                                    >
                                        1
                                    </button>
                                    @if($startPage > 2)
                                        <span class="rc-pagination-btn" style="border: none; cursor: default;">...</span>
                                    @endif
                                @endif
                                
                                @for($page = $startPage; $page <= $endPage; $page++)
                                    <button
                                        class="rc-pagination-btn {{ $page === $this->currentPage ? 'active' : '' }}"
                                        wire:click="goToPage({{ $page }})"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ $page }}
                                    </button>
                                @endfor
                                
                                @if($endPage < $totalPages)
                                    @if($endPage < $totalPages - 1)
                                        <span class="rc-pagination-btn" style="border: none; cursor: default;">...</span>
                                    @endif
                                    <button
                                        class="rc-pagination-btn"
                                        wire:click="goToPage({{ $totalPages }})"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ $totalPages }}
                                    </button>
                                @endif
                                
                                <button
                                    class="rc-pagination-btn"
                                    wire:click="nextPage"
                                    wire:loading.attr="disabled"
                                    @if($this->currentPage >= $totalPages) disabled @endif
                                >
                                    Selanjutnya
                                </button>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

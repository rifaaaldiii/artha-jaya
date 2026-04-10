<x-filament-panels::page>
    <div>
        <style>
            /* Container spacing - Filament style */
            .space-y-6 > * + * { margin-top: 1.5rem; }

            /* Card box - Filament style */
            .fi-fo-component-wp.fi-card {
                border-radius: 0.75rem;
                border: 1px solid rgba(209, 213, 219, 0.5);
                background: #fff;
                padding: 1.5rem;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            /* Flex helpers */
            .flex { display: flex; }
            .flex-col { flex-direction: column; }
            .flex-row { flex-direction: row; }
            .flex-wrap { flex-wrap: wrap; }
            .items-center { align-items: center; }
            .justify-between { justify-content: space-between; }
            .gap-4 { gap: 1rem; }
            .gap-3 { gap: 0.75rem; }
            .gap-2 { gap: 0.5rem; }

            /* Typography - Filament style */
            .fi-heading { 
                font-size: 1.125rem; 
                font-weight: 600; 
                color: rgb(17 24 39 / 1); 
                margin-bottom: 0.25rem; 
            }
            .fi-description { 
                font-size: 0.875rem; 
                color: rgb(107 114 128 / 1); 
                margin-bottom: 0; 
            }
            .fi-label { 
                font-size: 0.875rem; 
                font-weight: 500; 
                color: rgb(107 114 128 / 1); 
            }
            .fi-heading-lg { 
                font-size: 1.25rem; 
                font-weight: 600; 
                color: rgb(17 24 39 / 1); 
            }
            .fi-stat-value { 
                font-size: 1.875rem; 
                font-weight: 600; 
                color: rgb(17 24 39 / 1); 
            }
            .fi-text-success { color: rgb(5 150 105 / 1); }
            .fi-text-warning { color: rgb(217 119 6 / 1); }
            .fi-text-muted { color: rgb(107 114 128 / 1); }

            /* Table styles - Filament style */
            .fi-ta-ctn { 
                overflow-x: auto; 
                margin-top: 1rem;
                width: 100%;
            }
            .fi-ta { 
                width: 100%; 
                border-collapse: collapse; 
                font-size: 0.875rem;
            }
            .fi-ta th, .fi-ta td { 
                padding: 0.75rem 1rem; 
                text-align: left;
            }
            .fi-ta th { 
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: rgb(107 114 128 / 1);
                background: rgb(249 250 251 / 1);
                border-bottom: 1px solid rgb(229 231 235 / 1);
            }
            .fi-ta td { 
                color: rgb(17 24 39 / 1);
                border-bottom: 1px solid rgb(243 244 246 / 1);
            }
            .fi-ta tbody tr:hover {
                background: rgb(249 250 251 / 0.5);
            }
            .fi-ta .text-muted { 
                font-size: 0.75rem; 
                color: rgb(107 114 128 / 1);
            }
            .fi-ta .text-wrap {
                white-space: normal;
                word-wrap: break-word;
                max-width: 250px;
            }
            .fi-ta .text-nowrap {
                white-space: nowrap;
            }

            /* Stats Grid */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }
            .stat-card {
                border-radius: 0.75rem;
                border: 1px solid rgba(209, 213, 219, 0.5);
                background: #fff;
                padding: 1.25rem;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            .stat-label {
                font-size: 0.875rem;
                font-weight: 500;
                color: rgb(107 114 128 / 1);
                margin-bottom: 0.5rem;
            }
            .stat-value {
                font-size: 1.875rem;
                font-weight: 600;
                color: rgb(17 24 39 / 1);
            }

            /* Status breakdown - Filament style */
            .status-list > * + * { margin-top: 0.75rem; }
            .status-item {
                border: 1px solid rgba(209, 213, 219, 0.5);
                border-radius: 0.75rem;
                padding: 1rem 1.25rem;
                background: #fff;
            }
            .status-row { 
                display: flex; 
                align-items: center; 
                justify-content: space-between; 
                font-size: 0.875rem;
            }
            .status-bar-bg {
                width: 100%;
                background: rgb(243 244 246 / 1);
                height: 6px;
                border-radius: 9999px;
                margin-top: 0.5rem;
                overflow: hidden;
            }
            .status-bar {
                background: rgb(34 197 94 / 1);
                height: 6px;
                border-radius: 9999px;
            }

            /* Empty state */
            .fi-empty {
                border: 1px dashed rgb(209 213 219 / 1);
                border-radius: 0.75rem;
                padding: 2rem;
                text-align: center;
                font-size: 0.875rem;
                color: rgb(107 114 128 / 1);
                width: 100%;
            }

            /* Buttons spacing */
            .fi-actions { 
                display: flex; 
                gap: 0.5rem; 
                flex-wrap: wrap;
                margin-top: 1rem;
            }
            .fi-actions-end {
                justify-content: flex-end;
            }

            /* Pagination - Filament style */
            .fi-pagination {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 1rem;
                padding: 0.75rem 1rem;
                border-top: 1px solid rgb(229 231 235 / 1);
            }
            .fi-pagination-info {
                font-size: 0.875rem;
                color: rgb(107 114 128 / 1);
            }
            .fi-pagination-controls {
                display: flex;
                gap: 0.375rem;
                align-items: center;
            }
            .fi-pagination-btn {
                padding: 0.5rem 0.75rem;
                border: 1px solid rgb(229 231 235 / 1);
                border-radius: 0.5rem;
                background: #fff;
                color: rgb(55 65 81 / 1);
                font-size: 0.875rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.15s;
            }
            .fi-pagination-btn:hover:not(:disabled) {
                background: rgb(249 250 251 / 1);
                border-color: rgb(209 213 219 / 1);
            }
            .fi-pagination-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            .fi-pagination-btn.active {
                background: rgb(59 130 246 / 1);
                color: #fff;
                border-color: rgb(59 130 246 / 1);
            }

            /* Professional filter section styling */
            .fi-section {
                border: 1px solid rgb(229 231 235 / 1);
                border-radius: 0.5rem;
                background: rgb(249 250 251 / 1);
            }
            .fi-section-header {
                padding: 0.875rem 1rem;
                background: linear-gradient(135deg, rgb(249 250 251 / 1) 0%, rgb(243 244 246 / 1) 100%);
                border-bottom: 1px solid rgb(229 231 235 / 1);
            }
            .fi-section-content {
                padding: 1.25rem;
            }
        </style>
        @php
            $reportType = $this->filters['report_type'] ?? 'jasa';
            $previewIsEmpty = empty($this->previewRows);
        @endphp

        <div class="space-y-6">
        <!-- Filters -->
        <div class="fi-fo-component-wp fi-card" style="padding: 1.25rem;">
            {{ $this->filterForm }}
            
            <div style="margin-top: 1rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <x-filament::button 
                    color="gray" 
                    wire:click="resetFilters" 
                    icon="heroicon-m-arrow-path"
                    size="sm"
                >
                    Reset Filter
                </x-filament::button>
            </div>
        </div>

        <div class="fi-fo-component-wp fi-card">
            <div class="flex flex-col gap-3" style="gap:0.75rem;">
                <div>
                    <h3 class="fi-heading-lg">
                        Preview Data ({{ count($this->previewRows) }} dari {{ $this->resultCount }} data)
                    </h3>
                    <p class="fi-label">
                        Menampilkan {{ (($this->currentPage - 1) * $this->perPage) + 1 }} - {{ min(($this->currentPage * $this->perPage), $this->resultCount) }} dari {{ $this->resultCount }} data.
                    </p>
                </div>
                <div class="fi-actions">
                    {{-- <x-filament::button
                        color="success"
                        icon="heroicon-m-arrow-down-tray"
                        wire:click="downloadPdfByDateRange"
                        wire:loading.attr="disabled"
                        wire:target="downloadPdfByDateRange"
                        :disabled="$this->resultCount === 0"
                    >
                        Download PDF
                    </x-filament::button> --}}
                <a
                    href="{{ route('reports.pdf', array_filter(['report_type' => $reportType, 'format' => 'invoice', 'preview' => true, 'start_date' => $this->filters['start_date'] ?? null, 'end_date' => $this->filters['end_date'] ?? null])) }}"
                    target="_blank"
                    style="
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        font-size: 0.875rem;
                        font-weight: 500;
                        color: #fff;
                        background-color: #2563eb;
                        text-decoration: none;
                        transition: background-color 0.15s;
                        {{ $this->resultCount === 0 ? 'opacity: 0.5; cursor: not-allowed; pointer-events: none;' : '' }}
                    "
                    onmouseover="if({{ $this->resultCount === 0 ? 'false' : 'true' }}) this.style.backgroundColor='#3b82f6'"
                    onmouseout="if({{ $this->resultCount === 0 ? 'false' : 'true' }}) this.style.backgroundColor='#2563eb'"
                >
                    <svg style="height: 1rem; width: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Download Invoice
                </a>
                </div>
            </div>

            <div class="fi-ta-ctn">
                @if($previewIsEmpty)
                    <div class="fi-empty">
                        Belum ada data untuk ditampilkan. Silakan sesuaikan filter terlebih dahulu.
                    </div>
                @else
                    <table class="fi-ta">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if($reportType === 'produksi')
                                        <th>No Produksi</th>
                                        <th>No Ref</th>
                                        <th>Detail Item</th>
                                        <th>Jml Item</th>
                                        <th>Total Harga</th>
                                        <th>Branch</th>
                                        <th>Dibuat</th>
                                        <th>Action</th>
                                    @else
                                        <th>No Jasa</th>
                                        <th>No Ref</th>
                                        <th>Detail Item</th>
                                        <th>Jml Item</th>
                                        <th>Total Harga</th>
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
                                    <td class="text-muted">
                                        {{ (($this->currentPage - 1) * $this->perPage) + $index + 1 }}
                                    </td>
                                    @if($reportType === 'produksi')
                                        <td style="font-weight:600;">{{ $row['number'] ?? '-' }}</td>
                                        <td>{{ $row['no_ref'] ?? '-' }}</td>
                                        <td class="text-wrap">
                                            @if(!empty($row['items_summary']))
                                                <div style="white-space:pre-wrap; font-size:0.8125rem; line-height:1.3;">{{ $row['items_summary'] }}</div>
                                            @else
                                                <span style="color:#999;">-</span>
                                            @endif
                                            @if(!empty($row['note']))
                                                <p style="margin-top: 0.25rem; color: rgb(107 114 128 / 1); font-size: 0.75rem;">Catatan: {{ $row['note'] }}</p>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">{{ $row['items_count'] ?? 0 }}</td>
                                        <td style="text-align:right; font-weight:600;">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $row['branch'] ?? '-' }}</td>
                                        <td class="text-muted">{{ $row['created_at'] ?? '-' }}</td>
                                    @else
                                        <td style="font-weight:600;">{{ $row['number'] ?? '-' }}</td>
                                        <td>{{ $row['no_ref'] ?? '-' }}</td>
                                        <td class="text-wrap">
                                            @if(!empty($row['items_summary']))
                                                <div style="white-space:pre-wrap; font-size:0.8125rem; line-height:1.3;">{{ $row['items_summary'] }}</div>
                                            @else
                                                <span style="color:#999;">-</span>
                                            @endif
                                            @if(!empty($row['note']))
                                                <p style="margin-top: 0.25rem; color: rgb(107 114 128 / 1); font-size: 0.75rem;">Catatan: {{ $row['note'] }}</p>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">{{ $row['items_count'] ?? 0 }}</td>
                                        <td style="text-align:right; font-weight:600;">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $row['customer'] ?? '-' }}</td>
                                        <td class="text-wrap">{{ $row['petugas'] ?? '-' }}</td>
                                        <td class="text-muted">{{ $row['scheduled_at'] ?? '-' }}</td>
                                    @endif
                                    <td style="white-space: nowrap;">
                                        <div style="display: inline-flex; gap: 0.5rem;">
                                            {{-- <button
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
                                                    <span>PDF</span>
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
                                            </button> --}}
                                            <a
                                                href="{{ route('reports.pdf', ['report_type' => $reportType, 'single_number' => $row['number'], 'format' => 'invoice', 'preview' => true]) }}"
                                                target="_blank"
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
                                                    background-color: #2563eb;
                                                    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
                                                    transition: background 0.15s;
                                                    cursor: pointer;
                                                    outline: none;
                                                    text-decoration: none;
                                                "
                                                title="Preview Invoice untuk {{ $row['number'] }}"
                                                onmouseover="this.style.backgroundColor='#3b82f6'"
                                                onmouseout="this.style.backgroundColor='#2563eb'"
                                            >
                                                <svg style="height: 1rem; width: 1rem; color: #fff; margin-right: 0.22rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                <span>Invoice</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($this->resultCount > $this->perPage)
                        <div class="fi-pagination">
                            <div class="fi-pagination-info">
                                Menampilkan {{ (($this->currentPage - 1) * $this->perPage) + 1 }} - {{ min(($this->currentPage * $this->perPage), $this->resultCount) }} dari {{ $this->resultCount }} data
                            </div>
                            <div class="fi-pagination-controls">
                                <button
                                    class="fi-pagination-btn"
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
                                        class="fi-pagination-btn"
                                        wire:click="goToPage(1)"
                                        wire:loading.attr="disabled"
                                    >
                                        1
                                    </button>
                                    @if($startPage > 2)
                                        <span class="fi-pagination-btn" style="border: none; cursor: default;">...</span>
                                    @endif
                                @endif
                                
                                @for($page = $startPage; $page <= $endPage; $page++)
                                    <button
                                        class="fi-pagination-btn {{ $page === $this->currentPage ? 'active' : '' }}"
                                        wire:click="goToPage({{ $page }})"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ $page }}
                                    </button>
                                @endfor
                                
                                @if($endPage < $totalPages)
                                    @if($endPage < $totalPages - 1)
                                        <span class="fi-pagination-btn" style="border: none; cursor: default;">...</span>
                                    @endif
                                    <button
                                        class="fi-pagination-btn"
                                        wire:click="goToPage({{ $totalPages }})"
                                        wire:loading.attr="disabled"
                                    >
                                        {{ $totalPages }}
                                    </button>
                                @endif
                                
                                <button
                                    class="fi-pagination-btn"
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

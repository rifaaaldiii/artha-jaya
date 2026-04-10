<x-filament-panels::page>
    <style>
        .report-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .report-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            padding: 24px;
        }

        .dark .report-card {
            background: #1f2937;
            border-color: #374151;
        }

        .report-card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }

        .dark .report-card-title {
            color: #f9fafb;
        }

        .report-actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .report-table-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .dark .report-table-card {
            background: #1f2937;
            border-color: #374151;
        }

        .report-table-header {
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .dark .report-table-header {
            border-color: #374151;
        }

        .report-table-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .dark .report-table-title {
            color: #f9fafb;
        }

        .report-table-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .dark .report-table-subtitle {
            color: #9ca3af;
        }

        .report-table-container {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table thead {
            background-color: #f9fafb;
        }

        .dark .report-table thead {
            background-color: rgba(17, 24, 39, 0.5);
        }

        .report-table th {
            padding: 12px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        .dark .report-table th {
            color: #9ca3af;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.15s ease;
        }

        .dark .report-table tbody tr {
            border-color: #374151;
        }

        .report-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .dark .report-table tbody tr:hover {
            background-color: rgba(55, 65, 81, 0.5);
        }

        .report-table td {
            padding: 16px 24px;
            font-size: 14px;
            color: #374151;
        }

        .dark .report-table td {
            color: #d1d5db;
        }

        .report-table .number-cell {
            font-weight: 500;
            color: #111827;
        }

        .dark .report-table .number-cell {
            color: #f9fafb;
        }

        .report-table .amount-cell {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }

        .dark .report-table .amount-cell {
            color: #f9fafb;
        }

        .status-badge {
            display: inline-flex;
            padding: 4px 12px;
            font-size: 12px;
            line-height: 20px;
            font-weight: 600;
            border-radius: 9999px;
        }

        .status-gray {
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .status-gray {
            background-color: #374151;
            color: #d1d5db;
        }

        .status-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .dark .status-blue {
            background-color: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
        }

        .status-yellow {
            background-color: #fef3c7;
            color: #92400e;
        }

        .dark .status-yellow {
            background-color: rgba(146, 64, 14, 0.3);
            color: #fcd34d;
        }

        .status-green {
            background-color: #dcfce7;
            color: #166534;
        }

        .dark .status-green {
            background-color: rgba(22, 101, 52, 0.3);
            color: #86efac;
        }

        .status-purple {
            background-color: #f3e8ff;
            color: #6b21a8;
        }

        .dark .status-purple {
            background-color: rgba(107, 33, 168, 0.3);
            color: #d8b4fe;
        }

        .btn-invoice {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            background-color: #16a34a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.15s ease;
        }

        .btn-invoice:hover {
            background-color: #15803d;
        }

        .btn-invoice svg {
            width: 16px;
            height: 16px;
            margin-right: 6px;
        }

        .pagination-section {
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
        }

        .dark .pagination-section {
            border-color: #374151;
        }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pagination-info {
            font-size: 14px;
            color: #374151;
        }

        .dark .pagination-info {
            color: #d1d5db;
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-pagination {
            padding: 6px 12px;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-pagination:hover:not(:disabled) {
            background-color: #f9fafb;
        }

        .btn-pagination:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .dark .btn-pagination {
            background-color: #374151;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .dark .btn-pagination:hover:not(:disabled) {
            background-color: #4b5563;
        }

        .btn-pagination.active {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .empty-state {
            padding: 48px;
            text-align: center;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            color: #9ca3af;
        }

        .dark .empty-state-icon {
            color: #4b5563;
        }

        .empty-state-text {
            font-size: 18px;
            color: #6b7280;
        }

        .dark .empty-state-text {
            color: #9ca3af;
        }

        .empty-state-hint {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 8px;
        }

        .dark .empty-state-hint {
            color: #6b7280;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }
    </style>

    <div class="report-page">
        {{-- Filter Section --}}
        <div class="report-card">
            <h2 class="report-card-title">Filter Laporan</h2>
            {{ $this->filterForm }}
            
            <div class="report-actions">
                <x-filament::button color="gray" wire:click="resetFilters">
                    <x-filament::icon icon="heroicon-m-arrow-path" class="w-4 h-4 mr-1" />
                    Reset
                </x-filament::button>
                
                <x-filament::button color="success" wire:click="downloadFilteredPdf">
                    <x-filament::icon icon="heroicon-m-arrow-down-tray" class="w-4 h-4 mr-1" />
                    Download PDF
                </x-filament::button>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="report-table-card">
            <div class="report-table-header">
                <div>
                    <h2 class="report-table-title">
                        @if($filters['report_type'] === 'jasa')
                            Data Jasa & Layanan
                        @else
                            Data Produksi
                        @endif
                    </h2>
                    <p class="report-table-subtitle">
                        Total: {{ $resultCount }} record
                        @if($filters['start_date'] || $filters['end_date'])
                            | Periode: {{ $filters['start_date'] ?? 'Awal' }} - {{ $filters['end_date'] ?? 'Akhir' }}
                        @endif
                    </p>
                </div>
            </div>

            @if(count($previewRows) > 0)
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            
                            @if($filters['report_type'] === 'jasa')
                            <th>No Jasa</th>
                            <th>No Ref</th>
                            <th>Pelanggan</th>
                            <th>Petugas</th>
                            <th>Jadwal</th>
                            @else
                            <th>No Produksi</th>
                            <th>No Ref</th>
                            <th>Branch</th>
                            <th>Team</th>
                            @endif
                            
                            <th>Status</th>
                            <th class="text-right">Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewRows as $index => $row)
                        <tr>
                            <td class="whitespace-nowrap">
                                {{ (($currentPage - 1) * $perPage) + $index + 1 }}
                            </td>
                            
                            @if($filters['report_type'] === 'jasa')
                            <td class="whitespace-nowrap number-cell">
                                {{ $row['number'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['no_ref'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['customer'] ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['petugas'] ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['scheduled_at'] ?? '-' }}
                            </td>
                            @else
                            <td class="whitespace-nowrap number-cell">
                                {{ $row['number'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['no_ref'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['branch'] }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ $row['team'] ?? '-' }}
                            </td>
                            @endif
                            
                            <td class="whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'jasa baru' => 'status-gray',
                                        'terjadwal' => 'status-blue',
                                        'selesai dikerjakan' => 'status-yellow',
                                        'selesai' => 'status-green',
                                        'produksi baru' => 'status-gray',
                                        'siap produksi' => 'status-blue',
                                        'dalam pengerjaan' => 'status-yellow',
                                        'produksi siap diambil' => 'status-purple',
                                    ];
                                    $statusClass = $statusColors[$row['status']] ?? 'status-gray';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucwords($row['status']) }}
                                </span>
                            </td>
                            
                            <td class="whitespace-nowrap amount-cell">
                                Rp {{ number_format($row['total_harga'], 0, ',', '.') }}
                            </td>
                            
                            <td class="whitespace-nowrap">
                                {{ $row['created_at'] }}
                            </td>
                            
                            <td class="whitespace-nowrap text-center">
                                <button onclick="window.openInvoice('{{ $row['number'] }}', '{{ $filters['report_type'] }}')"
                                        class="btn-invoice">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Invoice
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @php
                $totalPages = ceil($resultCount / $perPage);
            @endphp
            
            @if($totalPages > 1)
            <div class="pagination-section">
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan {{ (($currentPage - 1) * $perPage) + 1 }} - {{ min($currentPage * $perPage, $resultCount) }} dari {{ $resultCount }} data
                    </div>
                    
                    <div class="pagination-buttons">
                        <x-filament::button size="sm" color="gray" wire:click="previousPage" :disabled="$currentPage <= 1">
                            Previous
                        </x-filament::button>
                        
                        @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        <x-filament::button 
                            size="sm" 
                            :color="$i === $currentPage ? 'primary' : 'gray'"
                            wire:click="goToPage({{ $i }})">
                            {{ $i }}
                        </x-filament::button>
                        @endfor
                        
                        <x-filament::button size="sm" color="gray" wire:click="nextPage" :disabled="$currentPage >= $totalPages">
                            Next
                        </x-filament::button>
                    </div>
                </div>
            </div>
            @endif
            @else
            <div class="empty-state">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="empty-state-text">Tidak ada data untuk ditampilkan</p>
                <p class="empty-state-hint">Coba ubah filter atau rentang tanggal</p>
            </div>
            @endif
        </div>
    </div>

    <script>
        window.openInvoice = function(number, type) {
            const url = `/admin/report/download-invoice?number=${encodeURIComponent(number)}&type=${encodeURIComponent(type)}`;
            window.open(url, '_blank');
        };
    </script>
</x-filament-panels::page>

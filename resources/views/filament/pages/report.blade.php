<x-filament-panels::page>
    <style>
        .report-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
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

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .report-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .dark .report-title {
            color: #f9fafb;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
        }

        .report-table thead {
            background: linear-gradient(to right, #eff6ff, #eef2ff);
        }

        .dark .report-table thead {
            background: linear-gradient(to right, rgba(30, 58, 138, 0.2), rgba(67, 56, 202, 0.2));
        }

        .report-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
        }

        .dark .report-table th {
            color: #d1d5db;
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
            padding: 12px 16px;
            font-size: 14px;
            color: #374151;
        }

        .dark .report-table td {
            color: #d1d5db;
        }

        .report-table .number-cell {
            font-weight: 600;
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

        .items-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
            background-color: #dbeafe;
            color: #1e40af;
        }

        .dark .items-badge {
            background-color: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .btn-report {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-report:hover {
            background-color: #1d4ed8;
        }

        .btn-report svg {
            width: 16px;
            height: 16px;
            margin-right: 6px;
        }

        .btn-invoice {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            background-color: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-invoice:hover {
            background-color: #15803d;
        }

        .btn-invoice svg {
            width: 16px;
            height: 16px;
            margin-right: 6px;
        }

        .pagination-container {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }

        .dark .pagination-container {
            border-color: #374151;
        }

        .pagination-info {
            font-size: 14px;
            color: #6b7280;
        }

        .dark .pagination-info {
            color: #9ca3af;
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-pagination {
            padding: 8px 16px;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 48px 16px;
        }

        .empty-state svg {
            margin: 0 auto;
            height: 48px;
            width: 48px;
            color: #9ca3af;
        }

        .empty-state p {
            margin-top: 8px;
            color: #6b7280;
        }

        .dark .empty-state p {
            color: #9ca3af;
        }

        /* Single Report View */
        .report-detail-header {
            display: flex;
            flex-direction: column;
            gap: 16px;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        @media (min-width: 640px) {
            .report-detail-header {
                flex-direction: row;
                align-items: flex-start;
            }
        }

        .dark .report-detail-header {
            border-color: #374151;
        }

        .report-detail-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #111827;
        }

        .dark .report-detail-title {
            color: #f9fafb;
        }

        .report-detail-number {
            color: #6b7280;
        }

        .report-detail-number strong {
            color: #111827;
        }

        .dark .report-detail-number {
            color: #9ca3af;
        }

        .dark .report-detail-number strong {
            color: #f9fafb;
        }

        .report-detail-actions {
            display: flex;
            gap: 12px;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-download svg {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        .btn-download-report {
            background-color: #2563eb;
            color: white;
        }

        .btn-download-report:hover {
            background-color: #1d4ed8;
        }

        .btn-download-invoice {
            background-color: #16a34a;
            color: white;
        }

        .btn-download-invoice:hover {
            background-color: #15803d;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (min-width: 768px) {
            .detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .detail-card {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 16px;
        }

        .dark .detail-card {
            background-color: rgba(55, 65, 81, 0.5);
        }

        .detail-label {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .dark .detail-label {
            color: #9ca3af;
        }

        .detail-value {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-top: 4px;
        }

        .dark .detail-value {
            color: #f9fafb;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }

        .dark .status-selesai {
            background-color: rgba(22, 101, 52, 0.3);
            color: #86efac;
        }

        .status-terjadwal {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .dark .status-terjadwal {
            background-color: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
        }

        .status-default {
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .status-default {
            background-color: #374151;
            color: #d1d5db;
        }

        .items-section {
            margin-bottom: 24px;
        }

        .items-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }

        .dark .items-section-title {
            color: #f9fafb;
        }

        .items-table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .dark .items-table-container {
            border-color: #374151;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead {
            background: linear-gradient(to right, #eff6ff, #eef2ff);
        }

        .dark .items-table thead {
            background: linear-gradient(to right, rgba(30, 58, 138, 0.2), rgba(67, 56, 202, 0.2));
        }

        .items-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
        }

        .dark .items-table th {
            color: #d1d5db;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.15s ease;
        }

        .dark .items-table tbody tr {
            border-color: #374151;
        }

        .items-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .dark .items-table tbody tr:hover {
            background-color: rgba(55, 65, 81, 0.5);
        }

        .items-table td {
            padding: 12px 16px;
            font-size: 14px;
            color: #374151;
        }

        .dark .items-table td {
            color: #d1d5db;
        }

        .items-table tfoot {
            background: linear-gradient(to right, #f9fafb, #f3f4f6);
        }

        .dark .items-table tfoot {
            background: linear-gradient(to right, #111827, #1f2937);
        }

        .items-table .total-label {
            padding: 12px 16px;
            font-weight: 700;
            color: #111827;
        }

        .dark .items-table .total-label {
            color: #f9fafb;
        }

        .items-table .total-amount {
            padding: 12px 16px;
            text-align: right;
            font-weight: 700;
            font-size: 18px;
            color: #2563eb;
        }

        .dark .items-table .total-amount {
            color: #60a5fa;
        }
    </style>

    <div class="report-container">
        {{-- Filter Form --}}
        <div class="report-card">
            <h3 class="report-card-title">Filter Laporan</h3>
            {{ $this->filterForm }}
        </div>

        {{-- Results Count --}}
        @if(!$singleNumber)
        <div class="report-card">
            <div class="report-header">
                <h2 class="report-title">
                    Hasil Laporan ({{ $resultCount }} data)
                </h2>
            </div>

            @if(count($previewRows) > 0)
            <div class="overflow-x-auto">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            @if($filters['report_type'] === 'jasa')
                            <th>No Jasa</th>
                            <th>No Ref</th>
                            <th>Pelanggan</th>
                            <th>Petugas</th>
                            @else
                            <th>No Produksi</th>
                            <th>No Ref</th>
                            <th>Branch</th>
                            @endif
                            <th>Items</th>
                            <th style="text-align: right;">Total</th>
                            <th>Tanggal</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewRows as $index => $row)
                        <tr>
                            <td>{{ (($currentPage - 1) * $perPage) + $index + 1 }}</td>
                            <td class="number-cell">{{ $row['number'] }}</td>
                            @if($filters['report_type'] === 'jasa')
                            <td>{{ $row['no_ref'] }}</td>
                            <td>{{ $row['customer'] ?? '-' }}</td>
                            <td>{{ $row['petugas'] ?? '-' }}</td>
                            @else
                            <td>{{ $row['no_ref'] }}</td>
                            <td>{{ $row['branch'] }}</td>
                            @endif
                            <td>
                                <span class="items-badge">
                                    {{ $row['items_count'] }} items
                                </span>
                            </td>
                            <td class="amount-cell">
                                Rp {{ number_format($row['total_harga'], 0, ',', '.') }}
                            </td>
                            <td>{{ $row['created_at'] ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button wire:click="generatePdf('{{ $row['number'] }}', '{{ $filters['report_type'] }}', 'report')"
                                            class="btn-report">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Report
                                    </button>
                                    <button wire:click="generatePdf('{{ $row['number'] }}', '{{ $filters['report_type'] }}', 'invoice')"
                                            class="btn-invoice">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Invoice
                                    </button>
                                </div>
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
            <div class="pagination-container">
                <div class="pagination-info">
                    Halaman {{ $currentPage }} dari {{ $totalPages }}
                </div>
                <div class="pagination-buttons">
                    <button wire:click="previousPage" 
                            @if($currentPage <= 1) disabled @endif
                            class="btn-pagination">
                        Previous
                    </button>
                    
                    @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                    <button wire:click="goToPage({{ $i }})"
                            class="btn-pagination {{ $i === $currentPage ? 'active' : '' }}">
                        {{ $i }}
                    </button>
                    @endfor

                    <button wire:click="nextPage"
                            @if($currentPage >= $totalPages) disabled @endif
                            class="btn-pagination">
                        Next
                    </button>
                </div>
            </div>
            @endif
            @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Tidak ada data untuk ditampilkan.</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Single Report View --}}
        @if($singleNumber && $reportData)
        <div class="report-card">
            <div class="report-detail-header">
                <div>
                    <h2 class="report-detail-title">
                        {{ $reportType === 'jasa' ? 'Laporan Jasa' : 'Laporan Produksi' }}
                    </h2>
                    <p class="report-detail-number">No: <strong>{{ $reportData['number'] }}</strong></p>
                </div>
                <div class="report-detail-actions">
                    <button wire:click="generatePdf('{{ $reportData['number'] }}', '{{ $reportType }}', 'report')"
                            class="btn-download btn-download-report">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Report
                    </button>
                    <button wire:click="generatePdf('{{ $reportData['number'] }}', '{{ $reportType }}', 'invoice')"
                            class="btn-download btn-download-invoice">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Invoice
                    </button>
                </div>
            </div>

            <div class="detail-grid">
                @if($reportType === 'jasa')
                <div class="detail-card">
                    <label class="detail-label">No Ref</label>
                    <p class="detail-value">{{ $reportData['no_ref'] ?? '-' }}</p>
                </div>
                <div class="detail-card">
                    <label class="detail-label">Pelanggan</label>
                    <p class="detail-value">{{ $reportData['pelanggan'] ?? '-' }}</p>
                </div>
                <div class="detail-card">
                    <label class="detail-label">Petugas</label>
                    <p class="detail-value">{{ $reportData['petugas'] ?? '-' }}</p>
                </div>
                <div class="detail-card">
                    <label class="detail-label">Jadwal</label>
                    <p class="detail-value">{{ $reportData['scheduled_at'] ?? '-' }}</p>
                </div>
                @else
                <div class="detail-card">
                    <label class="detail-label">No Ref</label>
                    <p class="detail-value">{{ $reportData['no_ref'] ?? '-' }}</p>
                </div>
                <div class="detail-card">
                    <label class="detail-label">Branch</label>
                    <p class="detail-value">{{ $reportData['branch'] ?? '-' }}</p>
                </div>
                @endif
                <div class="detail-card">
                    <label class="detail-label">Status</label>
                    <p class="detail-value">
                        @if($reportData['status'] === 'selesai')
                        <span class="status-badge status-selesai">{{ ucwords($reportData['status']) }}</span>
                        @elseif($reportData['status'] === 'terjadwal')
                        <span class="status-badge status-terjadwal">{{ ucwords($reportData['status']) }}</span>
                        @else
                        <span class="status-badge status-default">{{ ucwords($reportData['status']) }}</span>
                        @endif
                    </p>
                </div>
                <div class="detail-card">
                    <label class="detail-label">Tanggal</label>
                    <p class="detail-value">{{ $reportData['created_at'] }}</p>
                </div>
                @if(isset($reportData['catatan']))
                <div class="detail-card" style="grid-column: 1 / -1;">
                    <label class="detail-label">Catatan</label>
                    <p class="detail-value">{{ $reportData['catatan'] ?? '-' }}</p>
                </div>
                @endif
            </div>

            {{-- Items Table --}}
            @if(isset($reportData['items']) && count($reportData['items']) > 0)
            <div class="items-section">
                <h3 class="items-section-title">Detail Items</h3>
                <div class="items-table-container">
                    <table class="items-table">
                        <thead>
                            <tr>
                                @if($reportType === 'jasa')
                                <th>Jasa</th>
                                @else
                                <th>Produksi</th>
                                <th>Bahan</th>
                                @endif
                                <th>Jumlah</th>
                                <th style="text-align: right;">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['items'] as $item)
                            <tr>
                                @if($reportType === 'jasa')
                                <td>{{ $item['nama_jasa'] ?? $item['jenis_layanan'] }}</td>
                                @else
                                <td>{{ $item['nama_produksi'] }}</td>
                                <td>{{ $item['nama_bahan'] }}</td>
                                @endif
                                <td>{{ $item['jumlah'] }}</td>
                                <td style="text-align: right; font-weight: 500;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="{{ $reportType === 'jasa' ? 2 : 3 }}" class="total-label">Total</td>
                                <td class="total-amount">
                                    Rp {{ number_format($reportData['total_harga'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</x-filament-panels::page>

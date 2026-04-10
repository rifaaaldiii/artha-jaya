<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
        @page {
            size: A5 landscape;
            margin: 1.5cm 1.2cm 1.5cm 1.2cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #23272b;
            margin: 24px 32px;
            background: #fff;
        }
        .center-title-block {
            text-align: center;
            margin-bottom: 18px;
        }
        .center-header-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 5px;
            width: 100%;
        }
        .center-title-logo {
            height: 64px;
            width: 64px;
            object-fit: contain;
            display: block;
            margin-right: 10px;
        }
        .center-company-info {
            text-align: center;
            line-height: 1.25;
            flex: 1;
            font-size: 13px;
        }
        .info-title {
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            margin-top: 2.5px;
            margin-bottom: 1.3px;
        }
        .info-npsn {
            font-size: 10.5px;
            font-weight: 500;
            margin-bottom: 1px;
        }
        .info-address {
            font-size: 11px;
            margin-bottom: 1px;
        }
        .info-web {
            font-size: 10px;
            font-style: italic;
        }
        .center-separator {
            margin: 0 auto 10px auto;
            border: 0;
            border-top: 2px solid #000;
            width: 100%;
        }
        .center-separator-1 {
            margin: 10px auto 2px auto;
            border: 0;
            border-top: 2px solid #000;
            width: 100%;
        }
        .report-title {
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: 1.1px;
            margin-top: 10px;
            color: #34495e;
            text-transform: uppercase;
        }
        /* Table metadata */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0 20px 0;
            color: #23272b;
        }
        .meta-table td {
            padding: 2px 1px 2px 0;
            vertical-align: top;
            border: none;
        }
        .meta-table td.label {
            width: 110px;
            font-weight: 600;
            font-size: 9px;
            letter-spacing: 0.5px;
            color: #24292f;
        }
        .meta-table td.value {
            font-size: 9px;
            font-weight: 500;
        }
        /* Main data table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 10px;
            font-size: 10px;
            color: #23272b;
        }
        .data-table thead th {
            background: #f5f5f5;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #23272b;
            padding: 6px 4px;
            border-bottom: 1.2px solid #d4d7da;
            border-top: 1.2px solid #d4d7da;
            text-align: center;
        }
        .data-table th:nth-child(4), .data-table td:nth-child(4) {
            text-align: left;
        }
        .data-table th, .data-table td {
            border-right: 1px solid #e6e8eb;
            border-left: 1px solid #e6e8eb;
        }
        .data-table th:first-child, .data-table td:first-child {
            border-left: 1.2px solid #d4d7da;
        }
        .data-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #edeef0;
            font-size: 10px;
            vertical-align: top;
            text-align: center;
        }
        .data-table tbody tr:nth-child(odd) {
            background: #fafbfc;
        }
        .data-table tr:last-child td {
            border-bottom: 1.5px solid #cfd2d6;
        }
        .small {
            font-size: 9px;
            color: #575757;
        }
        .note {
            margin-top: 1px;
            font-size: 8.5px;
            color: #969696;
            font-style: italic;
        }
        tr {
            page-break-inside: avoid;
        }
        .status-summary-block {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin: 18px 0 16px 0;
        }
        .status-summary-item {
            border: 1px solid #e6e8eb;
            border-radius: 8px;
            min-width: 110px;
            padding: 8px 12px;
            background: #fafbfc;
            text-align: center;
        }
        .status-summary-item .status-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .status-summary-item .status-count {
            font-size: 16px;
            font-weight: 700;
            color: #23272b;
        }
        @media print {
            .center-title-block {
                margin-bottom: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="center-title-block">
        <div class="center-header-row">
            
            <div class="center-company-info">
                <div class="info-title">PT. ARTHA JAYA MAS</div>
                {{--  <div class="info-npsn">NPWP 206066463</div> --}}
                <div class="info-address">Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117</div>
                <div class="info-web">Telp : (+62) 8777-4467-228 || Email : Info@arthajaya.com</div>
            </div>
        </div>
        <hr class="center-separator-1">
        <hr class="center-separator">
        <div class="report-title">LAPORAN PRODUKSI</div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Rentang Tanggal</td>
            <td class="value">: {{ $summary['date_range'] ?? ($filters['start_date'] ?? 'Semua tanggal') }}</td>
        </tr>
        <tr>
            <td class="label">Total Data</td>
            <td class="value">: {{ $summary['total'] ?? count($rows) }} data</td>
        </tr>
        <tr>
            <td class="label">Dibuat Pada</td>
            <td class="value">: {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y H:i') }} WIB</td>
        </tr>
    </table>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:26px;">No.</th>
                <th style="width:75px;">Tanggal</th>
                <th style="width:60px;">No. Produksi</th>
                <th style="width:60px;">No. Ref</th>
                <th style="width:200px;">Detail Item</th>
                <th style="width:50px;">Jml Item</th>
                <th style="width:65px;">Branch</th>
                <th style="width:85px;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rows as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="small">{{ $row['created_at'] ?? '-' }}</span></td>
                <td>{{ $row['number'] ?? '-' }}</td>
                <td>{{ $row['no_ref'] ?? '-' }}</td>
                <td style="text-align:left;">
                    @if(!empty($row['items_summary']))
                        <div style="white-space:pre-wrap; font-size:9px; line-height:1.3;">{{ $row['items_summary'] }}</div>
                    @else
                        <span style="color:#999;">-</span>
                    @endif
                    @if(!empty($row['note']))
                        <div class="note">Catatan: {{ $row['note'] }}</div>
                    @endif
                </td>
                <td style="text-align:center;">{{ $row['items_count'] ?? 0 }}</td>
                <td>{{ $row['branch'] ?? '-' }}</td>
                <td style="text-align:right; font-weight:600;">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:14px; color:#b0b3b7; font-style:italic;">
                    Tidak ada data untuk ditampilkan.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</body>
</html>

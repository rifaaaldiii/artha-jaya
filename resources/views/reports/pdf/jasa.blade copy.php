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
            size: A4 portrait;
            margin: 2.5cm 1.7cm 2.5cm 1.7cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #23272b;
            margin: 24px 32px;
            background: #fff;
        }
        h1 {
            font-size: 15px;
            margin: 0 0 2px 0;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .center-title {
            text-align: center;
            margin-bottom: 5px;
        }
        .center-separator {
            margin: 0 auto 18px auto;
            border: 0;
            border-top: 2px solid #000;
            width: 100%;
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
        /* Print sharpening */
        @media print {
            h1 { font-size: 14px; }
            .data-table th, .data-table td {
                font-size: 9px;
            }
        }
    </style>
</head>
<body>

    <h1 class="center-title" style="letter-spacing:2px;">LAPORAN</h1>
    <h1 class="center-title" style="font-size:14px; font-weight:600; letter-spacing:1.2px;">JASA &amp; LAYANAN</h1>
    <hr class="center-separator">

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
                <th style="width:60px;">Nomor Jasa</th>
                <th style="width:60px;">Nomor Ref</th>
                <th style="width:220px;">Layanan &amp; Pelanggan</th>
                <th style="width:68px;">Jadwal</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rows as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['number'] ?? '-' }}</td>
                <td>{{ $row['reference'] ?? '-' }}</td>
                <td style="text-align:left;">
                    <span style="font-weight:600;">{{ $row['service'] ?? '-' }}</span><br>
                    <span class="small">{{ $row['customer'] ?? '-' }}</span>
                    @if(!empty($row['note']))
                        <div class="note">Catatan: {{ $row['note'] }}</div>
                    @endif
                </td>
                <td><span class="small">{{ $row['scheduled_at'] ?? '-' }}</span></td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:14px; color:#b0b3b7; font-style:italic;">
                    Tidak ada data untuk ditampilkan.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</body>
</html>

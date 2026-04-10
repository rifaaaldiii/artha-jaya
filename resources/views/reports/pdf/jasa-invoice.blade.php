<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', 'DejaVu Serif', Georgia, serif;
            font-size: 10pt;
            color: #1a1a1a;
            line-height: 1.5;
            background: #fff;
        }
        
        .page {
            width: 210mm;
            min-height: 148mm;
            padding: 12mm;
            position: relative;
        }
        
        /* Header Section */
        .letterhead {
            text-align: center;
            padding-bottom: 8mm;
            border-bottom: 2.5pt solid #1e3a8a;
            margin-bottom: 10mm;
        }
        
        .company-name {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1.5pt;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }
        
        .company-address {
            font-size: 9pt;
            color: #4a5568;
            line-height: 1.6;
        }
        
        /* Invoice Title */
        .invoice-title-section {
            text-align: center;
            margin: 8mm 0;
            padding: 5mm;
            background: #f8fafc;
            border: 1pt solid #e2e8f0;
        }
        
        .invoice-title {
            font-size: 18pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 3pt;
            margin-bottom: 2mm;
        }
        
        .invoice-number {
            font-size: 10pt;
            color: #4a5568;
        }
        
        /* Information Table */
        .info-section {
            margin-bottom: 8mm;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        
        .info-table td {
            padding: 2mm 3mm;
            vertical-align: top;
        }
        
        .info-label {
            width: 35mm;
            font-weight: bold;
            color: #1e3a8a;
        }
        
        .info-separator {
            width: 5mm;
            text-align: center;
        }
        
        .info-value {
            color: #1a1a1a;
        }
        
        /* Client Information Box */
        .client-box {
            border: 1pt solid #cbd5e1;
            padding: 5mm;
            margin-bottom: 8mm;
            background: #f8fafc;
        }
        
        .client-box-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 3mm;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        
        .client-info-grid {
            display: table;
            width: 100%;
        }
        
        .client-info-row {
            display: table-row;
        }
        
        .client-info-label {
            display: table-cell;
            width: 30mm;
            font-size: 9pt;
            color: #64748b;
            padding: 1mm 0;
        }
        
        .client-info-value {
            display: table-cell;
            font-size: 9pt;
            color: #1a1a1a;
            padding: 1mm 0;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8mm;
            font-size: 9pt;
        }
        
        .items-table thead {
            background: #1e3a8a;
            color: #ffffff;
        }
        
        .items-table th {
            padding: 3mm 4mm;
            text-align: left;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        
        .items-table th.center {
            text-align: center;
        }
        
        .items-table th.right {
            text-align: right;
        }
        
        .items-table tbody tr {
            border-bottom: 0.5pt solid #e2e8f0;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .items-table td {
            padding: 3mm 4mm;
            color: #1a1a1a;
        }
        
        .items-table td.center {
            text-align: center;
        }
        
        .items-table td.right {
            text-align: right;
        }
        
        .items-table td.bold {
            font-weight: bold;
        }
        
        /* Summary Section */
        .summary-section {
            margin-bottom: 8mm;
            page-break-inside: avoid;
        }
        
        .summary-table {
            width: 70mm;
            margin-left: auto;
            border-collapse: collapse;
            font-size: 9pt;
        }
        
        .summary-table td {
            padding: 2.5mm 4mm;
            border-bottom: 0.5pt solid #e2e8f0;
        }
        
        .summary-label {
            color: #64748b;
            font-weight: normal;
        }
        
        .summary-value {
            text-align: right;
            color: #1a1a1a;
        }
        
        .summary-total {
            background: #1e3a8a;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
        }
        
        .summary-total td {
            padding: 3.5mm 4mm;
            border: none;
        }
        
        .summary-total .summary-label {
            color: #ffffff;
        }
        
        .summary-total .summary-value {
            color: #ffffff;
        }
        
        /* Notes Section */
        .notes-section {
            margin-bottom: 8mm;
            padding: 4mm;
            border-left: 2pt solid #1e3a8a;
            background: #f8fafc;
        }
        
        .notes-title {
            font-size: 9pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2mm;
        }
        
        .notes-content {
            font-size: 8.5pt;
            color: #475569;
            line-height: 1.6;
        }
        
        /* Footer */
        .footer-section {
            margin-top: 10mm;
            padding-top: 5mm;
            border-top: 1pt solid #e2e8f0;
            text-align: center;
        }
        
        .footer-thank-you {
            font-size: 11pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2mm;
            letter-spacing: 1pt;
        }
        
        .footer-text {
            font-size: 8pt;
            color: #64748b;
            line-height: 1.6;
        }
        
        .footer-printed {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 3mm;
            font-style: italic;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 12mm;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-title {
            font-size: 9pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 15mm;
        }
        
        .signature-line {
            border-top: 0.5pt solid #1a1a1a;
            width: 60mm;
            margin: 0 auto;
            padding-top: 2mm;
            font-size: 8.5pt;
            color: #1a1a1a;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Letterhead -->
        <div class="letterhead">
            <div class="company-name">PT. ARTHA JAYA MAS</div>
            <div class="company-address">
                Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117<br>
                Telp: (+62) 8777-4467-228 | Email: Info@arthajaya.com
            </div>
        </div>
        
        <!-- Invoice Title -->
        <div class="invoice-title-section">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $row['number'] }}</div>
        </div>
        
        <!-- Invoice Information -->
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="info-label">No. Invoice</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ $row['number'] }}</td>
                    <td class="info-label">Tanggal</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="info-label">No. Referensi</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ $row['no_ref'] ?? '-' }}</td>
                    <td class="info-label">Status</td>
                    <td class="info-separator">:</td>
                    <td class="info-value">{{ strtoupper($row['status'] ?? '-') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Client Information -->
        <div class="client-box">
            <div class="client-box-title">Informasi Pelanggan & Layanan</div>
            <div class="client-info-grid">
                <div class="client-info-row">
                    <div class="client-info-label">Pelanggan</div>
                    <div class="client-info-value">: {{ $row['customer'] ?? '-' }}</div>
                </div>
                <div class="client-info-row">
                    <div class="client-info-label">Branch</div>
                    <div class="client-info-value">: {{ $row['branch'] ?? '-' }}</div>
                </div>
                <div class="client-info-row">
                    <div class="client-info-label">Petugas</div>
                    <div class="client-info-value">: {{ $row['petugas'] ?? '-' }}</div>
                </div>
                <div class="client-info-row">
                    <div class="client-info-label">Jadwal Pelaksanaan</div>
                    <div class="client-info-value">: {{ $row['scheduled_at'] ?? '-' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="center" width="8%">No.</th>
                    <th width="52%">Jenis Layanan</th>
                    <th class="right" width="20%">Jumlah</th>
                    <th class="right" width="20%">Harga</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($row['items']) && count($row['items']) > 0)
                    @foreach($row['items'] as $index => $item)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="bold">{{ $item['jenis_layanan'] ?? '-' }}</td>
                            <td class="right">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                            <td class="right bold">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 8mm; color: #94a3b8; font-style: italic;">
                            Tidak ada item layanan
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Subtotal</td>
                    <td class="summary-value">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Pajak (0%)</td>
                    <td class="summary-value">Rp 0</td>
                </tr>
                <tr class="summary-total">
                    <td class="summary-label">TOTAL</td>
                    <td class="summary-value">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Notes -->
        @if(!empty($row['note']))
        <div class="notes-section">
            <div class="notes-title">Catatan:</div>
            <div class="notes-content">{{ $row['note'] }}</div>
        </div>
        @endif
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Pemberi Kerja</div>
                <div class="signature-line">( .................................... )</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">PT. Artha Jaya Mas</div>
                <div class="signature-line">( .................................... )</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer-section">
            <div class="footer-thank-you">TERIMA KASIH</div>
            <div class="footer-text">
                Atas kepercayaan Anda kepada PT. Artha Jaya Mas<br>
                Pembayaran dapat ditransfer ke rekening yang telah disebutkan
            </div>
            <div class="footer-printed">
                Invoice ini dicetak pada {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y H:i') }} WIB
            </div>
        </div>
    </div>
</body>
</html>

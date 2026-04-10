<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A5 landscape;
            margin: 8mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', 'Courier', monospace;
            font-size: 8pt;
            color: #000;
            line-height: 1.3;
        }
        
        .page {
            width: 100%;
            max-width: 210mm;
        }
        
        /* Header */
        .header {
            text-align: center;
            border-bottom: 1.5pt solid #000;
            padding-bottom: 3mm;
            margin-bottom: 4mm;
        }
        
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1pt;
            margin-bottom: 1mm;
        }
        
        .company-address {
            font-size: 7pt;
            line-height: 1.4;
        }
        
        /* Title */
        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 3mm 0;
        }
        
        .title {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 2pt;
        }
        
        .invoice-info {
            text-align: right;
            font-size: 7.5pt;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 3mm;
            font-size: 7.5pt;
            width: auto;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.5mm;
        }
        
        .info-label {
            width: 28mm;
            font-weight: bold;
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3mm;
            font-size: 7.5pt;
        }
        
        .items-table th {
            border-top: 1pt solid #000;
            border-bottom: 0.5pt solid #000;
            padding: 1.5mm 2mm;
            text-align: left;
            font-weight: bold;
            font-size: 7pt;
        }
        
        .items-table th.center {
            text-align: center;
        }
        
        .items-table th.right {
            text-align: right;
        }
        
        .items-table td {
            padding: 1.5mm 2mm;
            border-bottom: 0.25pt solid #ccc;
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
        
        /* Summary */
        .summary-box {
            float: right;
            width: 65mm;
            border: 0.5pt solid #000;
            padding: 2mm;
            margin-bottom: 3mm;
            font-size: 7.5pt;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }
        
        .summary-row.total {
            border-top: 0.5pt solid #000;
            padding-top: 1mm;
            margin-top: 1mm;
            font-weight: bold;
            font-size: 9pt;
        }
        
        /* Notes */
        .notes {
            clear: both;
            border: 0.5pt solid #000;
            padding: 2mm;
            margin-bottom: 3mm;
            font-size: 7pt;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 1mm;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 4mm;
            padding-top: 3mm;
            border-top: 0.5pt solid #000;
            font-size: 7pt;
        }
        
        .thank-you {
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 1.5pt;
            margin-bottom: 1mm;
        }
        
        /* Signature */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 6mm;
            font-size: 7.5pt;
        }
        
        .signature-box {
            width: 60mm;
            text-align: center;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 10mm;
        }
        
        .signature-line {
            border-top: 0.5pt solid #000;
            padding-top: 1mm;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="company-name">PT. ARTHA JAYA MAS</div>
            <div class="company-address">
                Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117<br>
                Telp: (+62) 8777-4467-228
            </div>
        </div>
        
        <!-- Title -->
        <div class="title-row">
            <div class="title">INVOICE</div>
            <div class="invoice-info">
                <div>No: {{ $row['number'] }}</div>
                <div>Tanggal: {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y') }}</div>
            </div>
        </div>
        
        <!-- Info -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">No. Referensi : {{ $row['no_ref'] ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Branch : {{ $row['branch'] ?? '-' }}</div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="center" width="8%">No</th>
                    <th width="57%">Jenis Layanan</th>
                    <th class="center" width="15%">Qty</th>
                    <th class="right" width="20%">Harga</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($row['items']) && count($row['items']) > 0)
                    @foreach($row['items'] as $index => $item)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="bold">{{ $item['jenis_layanan'] ?? '-' }}</td>
                            <td class="center">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 4mm;">
                            Tidak ada item layanan
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Pajak (0%)</span>
                <span>Rp 0</span>
            </div>
            <div class="summary-row total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Notes -->
        @if(!empty($row['note']))
        <div class="notes">
            <div class="notes-title">Catatan:</div>
            <div>{{ $row['note'] }}</div>
        </div>
        @endif
        
        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Pemberi Kerja</div>
                <div class="signature-line">( ......................... )</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">PT. Artha Jaya Mas</div>
                <div class="signature-line">( ......................... )</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">TERIMA KASIH</div>
            <div>Invoice ini dicetak pada {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y H:i') }} WIB</div>
        </div>
    </div>
</body>
</html>

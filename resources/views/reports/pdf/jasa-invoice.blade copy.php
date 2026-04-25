<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A5 landscape;
            /* margin: 8mm; */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            color: #000;
            line-height: 1.1;
        }
        
        .page {
            width: 100%;
            max-width: 210mm;
            padding: 2mm 5mm 0;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 6px;
            border-bottom: 2px double #000;
            padding-bottom: 6px;
        }
        
        .header-top {
            margin-bottom: 8px;
        }
        
        .header-top .company-name {
            display: inline-block;
            width: 50%;
            text-align: left;
            vertical-align: middle;
        }
        
        .header-top .invoice-title {
            display: inline-block;
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }
        
        .company-name {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .company-address {
            font-size: 7pt;
            margin-bottom: 1px;
            color: #333;
        }
        
        .invoice-title {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        /* Document Number */
        .document-number {
            text-align: right;
            font-size: 7pt;
            margin-bottom: 6px;
            font-weight: bold;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 8px;
            padding: 5px;
        }
        
        .info-grid {
            width: 100%;
        }
        
        .info-row {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-column {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }
        
        .info-column.right {
            margin-left: 4%;
        }
        
        .info-item {
            margin-bottom: 2px;
            font-size: 7pt;
            width: 100%;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
            vertical-align: top;
        }
        
        .info-value {
            display: inline-block;
            width: auto;
        }
        
        /* Table Section */
        .table-section {
            margin-bottom: 8px;
        }
        
        .table-header {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 7pt;
        }
        
        .items-table th {
            border: 1px solid #000;
            padding: 3px 3px;
            text-align: center;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
        }
        
        .items-table th.right {
            text-align: right;
        }
        
        .items-table td {
            border: 1px solid #000;
            padding: 3px 3px;
            vertical-align: middle;
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
        
        .total-row {
            font-weight: bold;
        }
        
        /* Amount Words */
        .amount-words {
            margin-top: 5px;
            padding: 4px;
            border: 1px solid #000;
            font-size: 7pt;
        }
        
        .amount-label {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        /* Summary */
        .summary-section {
            text-align: right;
            margin-bottom: 8px;
        }
        
        .summary-box {
            display: inline-block;
            width: 65mm;
            border: 1px solid #000;
            padding: 5px;
            font-size: 7.5pt;
            text-align: left;
        }
        
        .summary-row {
            width: 100%;
            margin-bottom: 1mm;
        }
        
        .summary-row span:first-child {
            display: inline-block;
            width: 50%;
        }
        
        .summary-row span:last-child {
            display: inline-block;
            width: 50%;
            text-align: right;
        }
        
        .summary-row.total {
            border-top: 0.5pt solid #000;
            padding-top: 1mm;
            margin: 1mm 0 0;
            font-weight: bold;
            font-size: 9pt;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 15px;
            width: 100%;
        }
        
        .signature-row {
            width: 100%;
        }
        
        .signature-box {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: top;
            margin: 0 1.5%;
            font-size: 7pt;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            font-size: 7pt;
        }
        
        .signature-subtitle {
            font-size: 7pt;
            color: #666;
            margin-bottom: 50px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 0;
            padding-top: 3px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 2px;
            font-size: 8pt;
        }
        
        .signature-position {
            font-size: 6pt;
            color: #555;
            margin-top: 1px;
        }
        
        /* Footer */
        .footer {
            margin-top: 8px;
            font-size: 7pt;
            font-style: italic;
            text-align: center;
            color: #555;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="company-name">PT. ARTHA JAYA MAS</div>
                <div class="invoice-title">INVOICE</div>
            </div>
            <div>
                <div class="company-address">Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117</div>
                <div class="company-address">Telp: (+62) 8777-4467-228</div>
            </div>
        </div>
        
        <!-- Document Number -->
        <div class="document-number">
            No: {{ $row['number'] }}
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-grid">
                <!-- Left Column: Invoice Info -->
                <div class="info-column">
                    <div class="info-item">
                        <span class="info-label">No. Referensi</span>
                        <span class="info-value">: {{ $row['no_ref'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">: {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Branch</span>
                        <span class="info-value">: {{ $row['branch'] ?? '-' }}</span>
                    </div>
                </div>
                
                <!-- Right Column: Customer Info -->
                <div class="info-column right">
                    <div class="info-item">
                        <span class="info-label">Nama Customer</span>
                        <span class="info-value">: {{ $row['pelanggan_nama'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Telepon</span>
                        <span class="info-value">: {{ $row['pelanggan_telepon'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jadwal</span>
                        <span class="info-value">: {{ $row['jadwal'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">: {{ $row['alamat'] ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-section">
            <div class="table-header">Rincian Jasa & Layanan</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 35%;">Nama Jasa & Layanan</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 25%;" class="right">Harga</th>
                        <th style="width: 25%;" class="right">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($row['items']) && count($row['items']) > 0)
                        @foreach($row['items'] as $index => $item)
                            <tr>
                                <td class="center">{{ $index + 1 }}</td>
                                <td>{{ $item['jenis_layanan'] ?? '-' }}</td>
                                <td class="center">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                                <td class="right">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                <td class="right">Rp {{ number_format(($item['jumlah'] ?? 0) * ($item['harga'] ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="center" style="padding: 4mm;">
                                Tidak ada item layanan
                            </td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="4" class="text-center"><strong>SUB TOTAL</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Amount in Words -->
            <div class="amount-words">
                <div class="amount-label">Terbilang:</div>
                <div><em># {{ strtoupper($row['terbilang'] ?? 'NOL RUPIAH') }} #</em></div>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-section">
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
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-row">
                <div class="signature-box">
                    <div class="signature-title">Admin Toko</div>
                    <div class="signature-subtitle">Penanggung Jawab</div>
                    <div class="signature-line">
                        <div class="signature-name">(Nama Admin Toko)</div>
                        <div class="signature-position">Customer Services</div>
                    </div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">Admin</div>
                    <div class="signature-subtitle">Koordinator</div>
                    <div class="signature-line">
                        <div class="signature-name">(Nama Admin)</div>
                        <div class="signature-position">Administrator</div>
                    </div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">Kepala Teknisi</div>
                    <div class="signature-subtitle">Pelaksana</div>
                    <div class="signature-line">
                        <div class="signature-name">(Nama Kepala Teknisi)</div>
                        <div class="signature-position">Ketua Tim Pelaksana</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <em>Invoice ini dicetak pada {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y H:i') }} WIB</em>
        </div>
    </div>
</body>
</html>

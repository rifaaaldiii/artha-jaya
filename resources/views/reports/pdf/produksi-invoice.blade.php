<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Produksi</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 10mm 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            /* line-height: 1.1; */
            color: #000;
            background: #fff;
        }
        
        .container {
            /* border: 1px solid red; */
            /* width: 148mm;
            height: 210mm; */
            padding: 2mm 5mm 0;
            margin: 0 auto;
            background: #fff;
            position: relative;
        }
        
        .container::before {
            top: 0;
        }
        
        .container::after {
            bottom: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 6px;
            border-bottom: 2px double #000;
            padding-bottom: 6px;
            position: relative;
        }
        
        .header-top {
            margin-bottom: 8px;
        }
        
        .header-top .company-name {
            float: left;
            width: 50%;
            text-align: left;
        }
        
        .header-top .header-title {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .header-top:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .company-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .company-info {
            font-size: 7pt;
            margin-bottom: 1px;
            color: #333;
        }
        
        .header-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .referensi-number {
            text-align: right;
            font-size: 7pt;
            margin-bottom: 6px;
            font-weight: bold;
        }
        
        .info-section {
            margin-bottom: 8px;
            padding: 5px;
        }
        
        .section-header {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 8pt;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
            letter-spacing: 0.5px;
        }
        
        .info-grid {
            width: 100%;
        }
        
        .info-grid-left {
            float: left;
            width: 50%;
            padding-right: 4px;
        }
        
        .info-grid-right {
            float: right;
            width: 50%;
            padding-left: 4px;
        }
        
        .info-grid:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .info-item {
            margin-bottom: 2px;
            font-size: 7pt;
        }
        
        .info-item-address {
            margin-bottom: 2px;
            font-size: 7pt;
            position: relative;
            padding-left: 85px;
            min-height: 12px;
        }
        
        .info-item-address .info-label-address {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            font-weight: bold;
        }
        
        .info-item-address .info-value-address {
            display: block;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 80px;
        }
        
        .info-value {
            display: inline-block;
        }
        
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 7pt;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 3px 3px;
            text-align: left;
            vertical-align: middle;
        }
        
        th {
            font-weight: bold;
            text-align: center;
            font-size: 7pt;
            text-transform: uppercase;
        }
        
        .total-row {
            font-weight: bold;
            font-size: 7pt;
        }
        
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
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .signature-section {
            margin-top: 15px;
            width: 95%;
            position: absolute;
            bottom: 40px;
        }
        
        .signature-box {
            width: 30%;
            text-align: center;
            font-size: 7pt;
            float: left;
        }
        
        .signature-box:first-child {
            margin-right: 5%;
        }
        
        .signature-box:nth-child(2) {
            margin-right: 5%;
        }
        
        .signature-section:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            font-size: 7pt;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
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
        
        .formal-closing {
            /* margin-top: 8px; */
            font-size: 7pt;
            font-style: italic;
            text-align: center;
            color: #555;
            position: absolute;
            bottom: 10px;
            width: 95%;
        }
        
        /* Epson LX310 & Butterfly paper optimizations */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .container {
                padding: 0;
                margin: 0;
                border: none;
            }
            
            /* Remove perforation marks when printing */
            .container::before,
            .container::after {
                display: none;
            }
            
            table {
                page-break-inside: avoid;
            }
            
            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="company-name">PT. ARTHA JAYA MAS</div>
                <div class="header-title">FAKTUR</div>
            </div>
            <div>
                <div class="company-info">Jl. Raya Pandeglang No.km3, Cipare, Kec. Serang, Kota Serang, Banten 42117</div>
                <div class="company-info">Telp: +81287107768 | Email: info@arthajayamas.co.id</div>
                <!-- <div class="company-info">NPWP: 12.345.678.9-012.345</div> -->
            </div>
        </div>
        
        <!-- Document Number -->
        <div class="referensi-number">
            No. Referensi: <strong>{{ $row['no_ref'] ?? '-' }}</strong>
        </div>
        <div class="referensi-number">
            <strong>Serang, {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y') }}</strong>
        </div>

        <!-- Info Section: Work Order & Customer Data -->
        <div class="info-section">
            <div class="info-grid">
                <!-- Work Order Info -->
                <div class="info-grid-left">
                    <div class="info-item">
                        <span class="info-label">No. Produksi</span>
                        <span class="info-value">: {{ $row['number'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">: {{ $row['jadwal'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Branch</span>
                        <span class="info-value">: {{ $row['branch'] ?? '-' }}</span>
                    </div>
                </div>
                
                <!-- Customer Info -->
                <div class="info-grid-right">
                    <div class="info-item">
                        <span class="info-label">Nama Customer</span>
                        <span class="info-value">: {{ $row['pelanggan'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Telepon</span>
                        <span class="info-value">: {{ $row['pelanggan_telepon'] ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Order</span>
                        <span class="info-value">: {{ $row['jadwal'] ?? '-' }}</span>
                    </div>
                    <div class="info-item-address">
                        <span class="info-label-address">Alamat</span>
                        <span class="info-value-address">: {{ $row['alamat'] ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Items Table -->
        <div class="table-section">
            <div class="table-header">Rincian Produksi</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 25%;">Nama Produksi</th>
                        <th style="width: 25%;">Nama Bahan</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 17%;">Harga</th>
                        <th style="width: 18%;">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotal = 0;
                    @endphp
                    @if(isset($row['items']) && count($row['items']) > 0)
                        @foreach($row['items'] as $index => $item)
                            @php
                                $itemTotal = ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0);
                                $grandTotal += $itemTotal;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item['nama_produksi'] ?? '-' }}</td>
                                <td>{{ $item['nama_bahan'] ?? '-' }}</td>
                                <td class="text-center">{{ $item['jumlah'] ?? 0 }}</td>
                                <td class="text-right">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada item produksi</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="5" class="text-center"><strong>SUB TOTAL</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Amount in Words -->
            <div class="amount-words">
                <div class="amount-label">Terbilang:</div>
                <div><em># {{ terbilang($grandTotal) }} Rupiah #</em></div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Admin</div>
                <div style="font-size: 7pt; color: #666; margin-bottom: 50px;">Koordinator</div>
                <div class="signature-line">
                    <div class="signature-name">(___________________)</div>
                    <div class="signature-position">Administrator</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Kepala Produksi</div>
                <div style="font-size: 7pt; color: #666; margin-bottom: 50px;">Pelaksana</div>
                <div class="signature-line">
                    <div class="signature-name">(___________________)</div>
                    <div class="signature-position">Ketua Tim Produksi</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Customer</div>
                <div style="font-size: 7pt; color: #666; margin-bottom: 50px;">Penerima</div>
                <div class="signature-line">
                    <div class="signature-name">(___________________)</div>
                    <!-- <div class="signature-position">{{ $row['pelanggan'] ?? 'Pelanggan' }}</div> -->
                </div>
            </div>
        </div>
        
        <!-- Formal Closing -->
        <div class="formal-closing">
            <em>Demikian surat perintah produksi ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</em>
        </div>
    </div>
</body>
</html>

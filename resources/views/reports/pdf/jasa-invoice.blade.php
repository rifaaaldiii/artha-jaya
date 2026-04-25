<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Kerja</title>
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
            line-height: 1.1;
            color: #000;
            background: #fff;
        }
        
        .container {
            width: 148mm;
            height: 210mm;
            padding: 2mm 5mm 0;
            margin: 0 auto;
            border: 1px solid #000;
            border-radius: 0;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
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
            /* font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 1px; */
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
            /* border: 1px solid #000; */
            padding: 5px;
            /* background-color: #fafafa; */
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        .info-item {
            margin-bottom: 2px;
            font-size: 7pt;
            display: flex;
            align-items: flex-start;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 80px;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
            /* border: 1px solid #000;
            padding: 2px; */
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
            /* background-color: #d9d9d9; */
            font-weight: bold;
            text-align: center;
            font-size: 7pt;
            text-transform: uppercase;
        }
        
        .total-row {
            /* background-color: #f2f2f2; */
            font-weight: bold;
            font-size: 7pt;
        }
        
        .amount-words {
            margin-top: 5px;
            padding: 4px;
            border: 1px solid #000;
            /* background-color: #fafafa; */
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
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 30%;
            text-align: center;
            font-size: 7pt;
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
            margin-top: 8px;
            font-size: 7pt;
            font-style: italic;
            text-align: center;
            color: #555;
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
                <div class="header-title">SURAT PERINTAH KERJA</div>
            </div>
            <div>
                <div class="company-info">Jl. Contoh Alamat No. 123, Kota, Provinsi 12345</div>
                <div class="company-info">Telp: (021) 12345678 | Email: info@arthajayamas.co.id</div>
                <!-- <div class="company-info">NPWP: 12.345.678.9-012.345</div> -->
            </div>
        </div>
        
        <!-- Document Number -->
        <div class="referensi-number">
            No. Referensi: <strong>SPK/001/AJM/IV/2024</strong>
        </div>

        <!-- Info Section: Work Order & Customer Data -->
        <div class="info-section">
            <div class="info-grid">
                <!-- Work Order Info -->
                <div>
                    <div class="info-item">
                        <span class="info-label">No. Jasa</span>
                        <span class="info-value">: JSA/2024/001</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">: 24 April 2024</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Branch</span>
                        <span class="info-value">: AJC ( Artha Jaya Ciwaru )</span>
                    </div>
                </div>
                
                <!-- Customer Info -->
                <div>
                    <div class="info-item">
                        <span class="info-label">Nama Customer</span>
                        <span class="info-value">: Nama Pelanggan</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Telepon</span>
                        <span class="info-value">: 081234567890</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jadwal</span>
                        <span class="info-value">: 08 April 2024, 10:00 WIB</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">: Jl. Alamat Pelanggan No. 45 sdsadsasdasdsadsadasd</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Table -->
        <div class="table-section">
            <div class="table-header">Rincian Jasa & Layanan</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 35%;">Nama Jasa & layanan</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 25%;">Harga</th>
                        <th style="width: 25%;">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Pemasangan Closet sdsadsadsadsadasdsadsadsadasdasdsadsadsadas</td>
                        <td class="text-center">2</td>
                        <td class="text-right">Rp 1.000.000.000</td>
                        <td class="text-right">Rp 2.000.000.000</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Pemasangan AC 1/2 PK</td>
                        <td class="text-center">2</td>
                        <td class="text-right">Rp 1.000.000.000</td>
                        <td class="text-right">Rp 5.000.000.000</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" class="text-center"><strong>SUB TOTAL</strong></td>
                        <td class="text-right"><strong>Rp 7.000.000.000</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Amount in Words -->
            <div class="amount-words">
                <div class="amount-label">Terbilang:</div>
                <div><em># Tujuh Miliar Rupiah #</em></div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Admin Toko</div>
                <div style="font-size: 7pt; color: #666; margin-bottom: 50px;">Penanggung Jawab</div>
                <div class="signature-line">
                    <div class="signature-name">(Nama Admin Toko)</div>
                    <div class="signature-position">Customer Services</div>
                </div>
            </div>
            
            <div class="signature-box">
                <div class="signature-title">Admin</div>
                <div style="font-size: 7pt; color: #666; margin-bottom: 50px;">Koordinator</div>
                <div class="signature-line">
                    <div class="signature-name">(Nama Admin)</div>
                    <div class="signature-position">Administrator</div>
                </div>
            </div>
            
            <div class="signature-box">
                <div class="signature-title">Kepala Teknisi Lapangan</div>
                <div style="font-size: 7pt; color: #666; margin-bottom: 50px;">Pelaksana</div>
                <div class="signature-line">
                    <div class="signature-name">(Nama Kepala Teknisi Lapangan)</div>
                    <div class="signature-position">Ketua Tim Pelaksana</div>
                </div>
            </div>
        </div>
        
        <!-- Formal Closing -->
        <div class="formal-closing">
            <em>Demikian surat perintah kerja ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</em>
        </div>
    </div>
</body>
</html>

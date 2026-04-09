<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $row['number'] }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
            line-height: 1.3;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
            margin-bottom: 12px;
        }
        
        .company-info h1 {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        
        .company-details {
            font-size: 7.5px;
            color: #333;
            line-height: 1.5;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }
        
        .invoice-meta {
            font-size: 8px;
            line-height: 1.6;
        }
        
        .invoice-meta strong {
            font-weight: bold;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 12px;
            display: flex;
        }
        
        .info-box {
            padding: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            margin-bottom: 8px;
        }
        
        .info-box:last-child {
            margin-bottom: 0;
        }
        
        .info-box h3 {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #ddd;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 8px;
        }
        
        .info-label {
            color: #555;
        }
        
        .info-value {
            font-weight: bold;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8px;
        }
        
        .items-table thead {
            background: #000;
            color: #fff;
        }
        
        .items-table th {
            padding: 6px 5px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7px;
            letter-spacing: 0.3px;
            border: 1px solid #000;
        }
        
        .items-table th.center {
            text-align: center;
        }
        
        .items-table th.right {
            text-align: right;
        }
        
        .items-table tbody tr {
            border: 1px solid #e5e5e5;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .items-table td {
            padding: 5px;
            border: 1px solid #e5e5e5;
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
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        
        .summary-table {
            width: 200px;
            border: 1px solid #ddd;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 8px;
            border-bottom: 1px solid #e5e5e5;
            font-size: 8px;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #555;
        }
        
        .summary-row.total {
            background: #000;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            padding: 6px 8px;
        }
        
        .summary-row.total .summary-label {
            color: #fff;
        }
        
        /* Notes */
        .notes {
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-left: 3px solid #000;
            margin-bottom: 8px;
            font-size: 8px;
        }
        
        .notes strong {
            display: block;
            margin-bottom: 3px;
            font-size: 8px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 7.5px;
            color: #555;
            line-height: 1.5;
        }
        
        .footer strong {
            color: #000;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h1>PT. ARTHA JAYA MAS</h1>
            <div class="company-details">
                Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117<br>
                Telp: (+62) 8777-4467-228 | Email: Info@arthajaya.com
            </div>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <div class="invoice-meta">
                <div><strong>No:</strong> {{ $row['number'] }}</div>
                <div><strong>Tanggal:</strong> {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y') }}</div>
                @if(!empty($row['status']))
                <div><strong>Status:</strong> {{ strtoupper($row['status']) }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <div class="info-box">
            <h3>Informasi Produksi</h3>
            <div class="info-row">
                <span class="info-label">Branch:</span>
                <span class="info-value">{{ $row['branch'] ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Ref:</span>
                <span class="info-value">{{ $row['no_ref'] ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Branch:</span>
                <span class="info-value">{{ $row['branch'] ?? '-' }}</span>
            </div>
        </div>
        <div class="info-box">
            <h3>Detail Produksi</h3>
            <div class="info-row">
                <span class="info-label">Tanggal Dibuat:</span>
                <span class="info-value">{{ $row['created_at'] ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Item:</span>
                <span class="info-value">{{ $row['items_count'] ?? 0 }} item</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Selesai:</span>
                <span class="info-value">{{ $row['updated_at'] ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="center" width="5%">No</th>
                <th width="35%">Jenis Produksi</th>
                <th width="30%">Nama Bahan</th>
                <th class="center" width="12%">Jumlah</th>
                <th class="right" width="18%">Harga</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($row['items']) && count($row['items']) > 0)
                @foreach($row['items'] as $index => $item)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td class="bold">{{ $item['nama_produksi'] ?? '-' }}</td>
                        <td>{{ $item['nama_bahan'] ?? '-' }}</td>
                        <td class="center">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                        <td class="right bold">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; padding: 15px; color: #999;">
                        Tidak ada item produksi
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-table">
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span>Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Pajak (0%)</span>
                <span>Rp 0</span>
            </div>
            <div class="summary-row total">
                <span class="summary-label">TOTAL</span>
                <span>Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if(!empty($row['note']))
        <div class="notes">
            <strong>Catatan:</strong>
            {{ $row['note'] }}
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <strong>TERIMA KASIH</strong><br>
        Atas kepercayaan Anda kepada PT. Artha Jaya Mas<br>
        <span style="font-size: 7px;">Invoice ini dicetak pada {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y H:i') }} WIB</span>
    </div>
</body>
</html>

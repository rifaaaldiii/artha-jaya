<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $row['number'] }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 12mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #2d3748;
            line-height: 1.4;
            margin: 0 10px;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            margin-bottom: 16px;
            border-bottom: 2px solid #1a73e8;
        }
        
        .company-info h1 {
            font-size: 18px;
            font-weight: 600;
            color: #1a73e8;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .company-details {
            font-size: 7.5px;
            color: #718096;
            line-height: 1.5;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            font-size: 22px;
            font-weight: 300;
            color: #1a73e8;
            letter-spacing: 3px;
            margin-bottom: 6px;
        }
        
        .invoice-meta {
            font-size: 8px;
            line-height: 1.8;
            color: #4a5568;
        }
        
        .invoice-meta strong {
            font-weight: 600;
            color: #2d3748;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5px;
        }
        
        .items-table thead {
            background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
            color: #fff;
        }
        
        .items-table th {
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7px;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .items-table th.center {
            text-align: center;
        }
        
        .items-table th.right {
            text-align: right;
        }
        
        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .items-table tbody tr:last-child {
            border-bottom: 2px solid #1a73e8;
        }
        
        .items-table td {
            padding: 7px 6px;
            border: none;
            color: #2d3748;
        }
        
        .items-table td.center {
            text-align: center;
        }
        
        .items-table td.right {
            text-align: right;
        }
        
        .items-table td.bold {
            font-weight: 600;
            color: #1a202c;
        }
        
        /* Summary */
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }
        
        .summary-table {
            width: 220px;
            background: #f7fafc;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8.5px;
            color: #4a5568;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #718096;
            font-weight: 500;
        }
        
        .summary-row.total {
            background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
            color: #fff;
            font-weight: 700;
            font-size: 10px;
            padding: 8px 12px;
        }
        
        .summary-row.total .summary-label {
            color: #fff;
        }
        
        /* Notes */
        .notes {
            padding: 8px 10px;
            background: #f7fafc;
            border-left: 3px solid #1a73e8;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 8px;
            color: #4a5568;
            line-height: 1.5;
        }
        
        .notes strong {
            display: block;
            margin-bottom: 4px;
            font-size: 8px;
            color: #1a73e8;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 12px 0 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 7.5px;
            color: #718096;
            line-height: 1.6;
        }
        
        .footer strong {
            color: #1a73e8;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1px;
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

    <!-- Info Section - Removed -->

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="center" width="5%">No</th>
                <th width="38%">Jenis Produksi</th>
                <th width="27%">Nama Bahan</th>
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
                    <td colspan="5" style="text-align: center; padding: 20px; color: #a0aec0;">
                        Tidak ada item produksi
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary-section" style="float: right;">
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

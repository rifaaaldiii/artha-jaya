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
            margin: 1cm 1cm 1cm 1cm;
        }
        body {
            font-family: 'Inter', 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            color: #111827;
            background: #fff;
            line-height: 1.3;
        }
        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
        }
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .company-info {
            flex: 1;
        }
        .company-logo {
            height: 40px;
            width: 40px;
            object-fit: contain;
            margin-right: 8px;
        }
        .company-details h1 {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 1px 0;
            letter-spacing: -0.025em;
        }
        .company-details p {
            margin: 0.5px 0;
            color: #6b7280;
            font-size: 7.5px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 16px;
            font-weight: 700;
            color: #059669;
            margin: 0 0 2px 0;
            letter-spacing: -0.025em;
        }
        .invoice-number {
            font-size: 8px;
            color: #6b7280;
            margin: 1px 0;
        }
        .invoice-date {
            font-size: 7.5px;
            color: #6b7280;
        }
        /* Client Info */
        .client-info {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            padding: 8px;
            background: #f9fafb;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .client-details {
            flex: 1;
        }
        .client-details h3 {
            font-size: 8.5px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .client-details p {
            margin: 1px 0;
            color: #4b5563;
            font-size: 7.5px;
        }
        .client-details .label {
            font-weight: 500;
            color: #6b7280;
        }
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 7.5px;
        }
        .items-table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 5px 6px;
            text-align: left;
            border-bottom: 1px solid #d1d5db;
            font-size: 7px;
        }
        .items-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }
        .items-table tr:nth-child(even) {
            background: #fafafa;
        }
        .items-table .number {
            text-align: center;
            width: 25px;
        }
        .items-table .price {
            text-align: right;
            font-weight: 500;
        }
        .items-table .quantity {
            text-align: center;
        }
        /* Summary */
        .invoice-summary {
            display: flex;
            justify-content: flex-end;
            margin: 10px 0;
        }
        .summary-table {
            width: 200px;
            border-collapse: collapse;
            font-size: 7.5px;
        }
        .summary-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary-table .label {
            text-align: left;
            color: #6b7280;
            font-weight: 500;
        }
        .summary-table .value {
            text-align: right;
            color: #111827;
            font-weight: 500;
        }
        .summary-table .total {
            font-size: 9px;
            font-weight: 700;
            color: #059669;
            background: #f0fdf4;
            border-top: 2px solid #d1fae5;
        }
        .summary-table .total .value {
            color: #059669;
        }
        /* Footer */
        .invoice-footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 7px;
        }
        .notes {
            margin-top: 8px;
            padding: 6px;
            background: #f9fafb;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .notes h3 {
            font-size: 8px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 2px 0;
        }
        .notes p {
            margin: 0;
            color: #6b7280;
            font-size: 7px;
        }
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 8px;
            font-size: 6.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-selesai {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div style="display: flex; align-items: center;">
                    <!-- <img src="{{ public_path('logo.png') }}" alt="Logo" class="company-logo"> -->
                    <div class="company-details">
                        <h1>PT. ARTHA JAYA MAS</h1>
                        <p>Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117</p>
                        <p>Telp: (+62) 8777-4467-228 | Email: Info@arthajaya.com</p>
                    </div>
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">{{ $row['number'] }}</div>
                <div class="invoice-date">Tanggal: {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y') }}</div>
                @if(!empty($row['status']))
                    <span class="status-badge status-selesai">{{ $row['status'] }}</span>
                @endif
            </div>
        </div>

        <!-- Client Info -->
        <div class="client-info">
            <div class="client-details">
                <h3>Informasi Produksi</h3>
                <p><span class="label">Team:</span> {{ $row['team'] ?? '-' }}</p>
                <p><span class="label">No. Ref:</span> {{ $row['no_ref'] ?? '-' }}</p>
                <p><span class="label">Branch:</span> {{ $row['branch'] ?? '-' }}</p>
            </div>
            <div class="client-details">
                <h3>Detail Produksi</h3>
                <p><span class="label">Dibuat:</span> {{ $row['created_at'] ?? '-' }}</p>
                <p><span class="label">Jumlah Item:</span> {{ $row['items_count'] ?? 0 }}</p>
                <p><span class="label">Status:</span> {{ $row['status'] ?? '-' }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="number">No.</th>
                    <th>Jenis Produksi</th>
                    <th>Nama Bahan</th>
                    <th class="quantity">Jumlah</th>
                    <th class="price">Harga</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($row['items']) && count($row['items']) > 0)
                    @foreach($row['items'] as $index => $item)
                        <tr>
                            <td class="number">{{ $index + 1 }}</td>
                            <td>{{ $item['nama_produksi'] ?? '-' }}</td>
                            <td>{{ $item['nama_bahan'] ?? '-' }}</td>
                            <td class="quantity">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }} unit</td>
                            <td class="price">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 12px; color: #9ca3af;">
                            Tidak ada item produksi
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Summary -->
        <div class="invoice-summary">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Pajak (0%)</td>
                    <td class="value">Rp 0</td>
                </tr>
                <tr class="total">
                    <td class="label">TOTAL</td>
                    <td class="value">Rp {{ number_format($row['total_harga'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if(!empty($row['note']))
            <div class="notes">
                <h3>Catatan</h3>
                <p>{{ $row['note'] }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <p>Terima kasih atas kepercayaan Anda kepada PT. Artha Jaya Mas</p>
            <p>Invoice ini dicetak pada {{ $generatedAt->timezone(config('app.timezone','Asia/Jakarta'))->format('d/m/Y H:i') }} WIB</p>
        </div>
    </div>
</body>
</html>

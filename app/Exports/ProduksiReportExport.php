<?php

namespace App\Exports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ProduksiReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $branch;
    protected $rows;
    protected $rowNumber = 0;

    public function __construct($startDate = null, $endDate = null, $branch = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->branch = $branch;
        $this->rows = $this->getData();
    }

    protected function getData()
    {
        $query = Produksi::with(['pelanggan', 'team', 'items']);

        if ($this->startDate) {
            $query->whereDate('createdAt', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('createdAt', '<=', $this->endDate);
        }
        if ($this->branch) {
            $query->where('branch', $this->branch);
        }

        return $query->orderBy('createdAt', 'asc')->get();
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function map($produksi): array
    {
        $this->rowNumber++;

        $itemsArray = [];
        if ($produksi->items && $produksi->items->count() > 0) {
            foreach ($produksi->items as $item) {
                $itemsArray[] = "{$item->nama_produksi} - {$item->jumlah} unit";
            }
        }

        $teamName = $produksi->team?->nama ?? '-';

        return [
            $produksi->no_produksi,
            $produksi->jadwal ? $produksi->jadwal->format('d/m/Y H:i') : '-',
            $produksi->no_ref ?? '-',
            $produksi->pelanggan?->nama ?? '-',
            $teamName,
            implode("\n", $itemsArray),
            $produksi->items->count(),
            $produksi->items->sum('harga'),
            ucfirst($produksi->status),
            $produksi->catatan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function title(): string
    {
        return 'Laporan Produksi';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $event->sheet->getParent();

                // Set default font
                $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

                // Header Section - Company Info
                $sheet->setCellValue('A1', 'PT. ARTHA JAYA MAS');
                $sheet->mergeCells('A1:N1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A2', 'Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117');
                $sheet->mergeCells('A2:N2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A3', 'Telp : (+62) 8777-4467-228 || Email : Info@arthajaya.com');
                $sheet->mergeCells('A3:N3');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Separator
                $sheet->getStyle('A4:N4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                
                // Report Title
                $sheet->setCellValue('A5', 'REKAP PRODUKSI');
                $sheet->mergeCells('A5:N5');
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A5')->getFont();

                // Metadata
                $sheet->setCellValue('A6', 'Rentang Tanggal : ' . ($this->startDate ? \Carbon\Carbon::parse($this->startDate)->locale('id')->isoFormat('D MMMM YYYY') : 'Awal') . ' - ' . ($this->endDate ? \Carbon\Carbon::parse($this->endDate)->locale('id')->isoFormat('D MMMM YYYY') : 'Akhir'));
                $sheet->mergeCells('A6:N6');
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A6:A8')->getFont()->setSize(10);
                $sheet->getStyle('A6:A8')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

                // Headers Row
                $headerRow = 9;
                $headers = [
                    'A' => 'No.',
                    'B' => 'No. Referensi',
                    'C' => 'No. Produksi',
                    'D' => 'Tanggal',
                    'E' => 'Tanggal Selesai',
                    'F' => 'Branch',
                    'G' => 'Pelanggan',
                    'H' => 'Alamat Produksi',
                    'I' => 'Team',
                    'J' => 'Jenis Produksi',
                    'K' => 'Nama Bahan',
                    'L' => 'Qty',
                    'M' => 'Harga',
                    'N' => 'Total Harga',
                ];

                foreach ($headers as $col => $header) {
                    $sheet->setCellValue($col . $headerRow, $header);
                }

                // Style headers
                $sheet->getStyle('A' . $headerRow . ':N' . $headerRow)->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A' . $headerRow . ':N' . $headerRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A' . $headerRow . ':N' . $headerRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F5F5F5');
                $sheet->getStyle('A' . $headerRow . ':N' . $headerRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Data rows
                $dataStartRow = 10;
                $currentRow = $dataStartRow;
                $rowNumber = 1;
                $grandTotal = 0;

                foreach ($this->rows as $produksi) {
                    $teamName = $produksi->team?->nama ?? '-';

                    // Calculate grand total for this produksi
                    $produksiTotalHarga = 0;
                    if ($produksi->items && $produksi->items->count() > 0) {
                        foreach ($produksi->items as $item) {
                            $qty = $item->jumlah ?? 0;
                            $harga = $item->harga ?? 0;
                            $produksiTotalHarga += ($qty * $harga);
                        }
                    }
                    $grandTotal += $produksiTotalHarga;

                    // If has items, create row for each item
                    if ($produksi->items && $produksi->items->count() > 0) {
                        $itemStartRow = $currentRow;
                        $itemIndex = 0;

                        foreach ($produksi->items as $item) {
                            $jenisProduksi = $item->nama_produksi ?? '-';
                            $namaBahan = $item->nama_bahan ?? '-';
                            $qty = $item->jumlah ?? 0;
                            $harga = $item->harga ?? 0;
                            $totalHarga = $qty * $harga;

                            $sheet->setCellValue('A' . $currentRow, $itemIndex === 0 ? $rowNumber : '');
                            $sheet->setCellValue('B' . $currentRow, $itemIndex === 0 ? ($produksi->no_ref ?? '-') : '');
                            $sheet->setCellValue('C' . $currentRow, $itemIndex === 0 ? $produksi->no_produksi : '');
                            $sheet->setCellValue('D' . $currentRow, $itemIndex === 0 ? ($produksi->createdAt ? $produksi->jadwal->format('d/m/Y') : '-') : '');
                            $sheet->setCellValue('E' . $currentRow, $itemIndex === 0 ? ($produksi->updateAt ? $produksi->updateAt->format('d/m/Y') : '-') : '');
                            $sheet->setCellValue('F' . $currentRow, $itemIndex === 0 ? ($produksi->branch ?? '-') : '');
                            $sheet->setCellValue('G' . $currentRow, $itemIndex === 0 ? ($produksi->pelanggan?->nama ?? '-') : '');
                            $sheet->setCellValue('H' . $currentRow, $itemIndex === 0 ? ($produksi->alamat ?? $produksi->pelanggan?->alamat ?? '-') : '');
                            $sheet->setCellValue('I' . $currentRow, $itemIndex === 0 ? $teamName : '');
                            $sheet->setCellValue('J' . $currentRow, $jenisProduksi);
                            $sheet->setCellValue('K' . $currentRow, $namaBahan);
                            $sheet->setCellValue('L' . $currentRow, $qty);
                            $sheet->setCellValueExplicit('M' . $currentRow, $harga, DataType::TYPE_NUMERIC);
                            $sheet->setCellValueExplicit('N' . $currentRow, $totalHarga, DataType::TYPE_NUMERIC);

                            $itemIndex++;
                            $currentRow++;
                        }

                        // Merge common columns (A-I)
                        if ($itemIndex > 1) {
                            $sheet->mergeCells('A' . $itemStartRow . ':A' . ($currentRow - 1));
                            $sheet->mergeCells('B' . $itemStartRow . ':B' . ($currentRow - 1));
                            $sheet->mergeCells('C' . $itemStartRow . ':C' . ($currentRow - 1));
                            $sheet->mergeCells('D' . $itemStartRow . ':D' . ($currentRow - 1));
                            $sheet->mergeCells('E' . $itemStartRow . ':E' . ($currentRow - 1));
                            $sheet->mergeCells('F' . $itemStartRow . ':F' . ($currentRow - 1));
                            $sheet->mergeCells('G' . $itemStartRow . ':G' . ($currentRow - 1));
                            $sheet->mergeCells('H' . $itemStartRow . ':H' . ($currentRow - 1));
                            $sheet->mergeCells('I' . $itemStartRow . ':I' . ($currentRow - 1));
                        }
                        for ($r = $itemStartRow; $r < $currentRow; $r++) {
                            $sheet->getStyle('A' . $r . ':N' . $r)->getBorders()->getAllBorders()
                                ->setBorderStyle(Border::BORDER_THIN);
                            $sheet->getStyle('A' . $r . ':N' . $r)->getAlignment()
                                ->setVertical(Alignment::VERTICAL_TOP);
                            
                            // Format currency
                            $sheet->getStyle('M' . $r)->getNumberFormat()
                                ->setFormatCode('"Rp. "#,##0');
                            $sheet->getStyle('N' . $r)->getNumberFormat()
                                ->setFormatCode('"Rp. "#,##0');
                            
                            // Set wrap text for jenis produksi, nama bahan, and alamat
                            $sheet->getStyle('J' . $r)->getAlignment()->setWrapText(true);
                            $sheet->getStyle('K' . $r)->getAlignment()->setWrapText(true);
                            $sheet->getStyle('H' . $r)->getAlignment()->setWrapText(true);
                            
                            // Center alignment for No, No. Referensi, No. Produksi, and Qty
                            $sheet->getStyle('A' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('B' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('C' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('D' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('E' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('I' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('L' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('F' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('M' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                            $sheet->getStyle('N' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        }

                        $rowNumber++;
                    } else {
                        // If no items, create one row with dash
                        $sheet->setCellValue('A' . $currentRow, $rowNumber);
                        $sheet->setCellValue('B' . $currentRow, $produksi->no_ref ?? '-');
                        $sheet->setCellValue('C' . $currentRow, $produksi->no_produksi);
                        $sheet->setCellValue('D' . $currentRow, $produksi->createdAt ? $produksi->createdAt->format('d/m/Y') : '-');
                        $sheet->setCellValue('E' . $currentRow, $produksi->updateAt ? $produksi->updateAt->format('d/m/Y') : '-');
                        $sheet->setCellValue('F' . $currentRow, $produksi->branch ?? '-');
                        $sheet->setCellValue('G' . $currentRow, $produksi->pelanggan?->nama ?? '-');
                        $sheet->setCellValue('H' . $currentRow, $produksi->alamat ?? $produksi->pelanggan?->alamat ?? '-');
                        $sheet->setCellValue('I' . $currentRow, $teamName);
                        $sheet->setCellValue('J' . $currentRow, '-');
                        $sheet->setCellValue('K' . $currentRow, '-');
                        $sheet->setCellValue('L' . $currentRow, 0);
                        $sheet->setCellValueExplicit('M' . $currentRow, 0, DataType::TYPE_NUMERIC);
                        $sheet->setCellValueExplicit('N' . $currentRow, 0, DataType::TYPE_NUMERIC);

                        // Style data row
                        $sheet->getStyle('A' . $currentRow . ':N' . $currentRow)->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle('A' . $currentRow . ':N' . $currentRow)->getAlignment()
                            ->setVertical(Alignment::VERTICAL_TOP);
                        
                        // Format currency
                        $sheet->getStyle('M' . $currentRow)->getNumberFormat()
                            ->setFormatCode('"Rp. "#,##0');
                        $sheet->getStyle('N' . $currentRow)->getNumberFormat()
                            ->setFormatCode('"Rp. "#,##0');
                        
                        // Set wrap text for jenis produksi, nama bahan, and alamat
                        $sheet->getStyle('J' . $currentRow)->getAlignment()->setWrapText(true);
                        $sheet->getStyle('K' . $currentRow)->getAlignment()->setWrapText(true);
                        $sheet->getStyle('H' . $currentRow)->getAlignment()->setWrapText(true);
                        
                        // Center alignment for No, No. Referensi, No. Produksi, and Qty
                        $sheet->getStyle('A' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('B' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('C' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('L' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('F' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $currentRow++;
                        $rowNumber++;
                    }
                }

                // Grand Total Row
                $totalRow = $currentRow;
                $sheet->setCellValue('A' . $totalRow, 'GRAND TOTAL');
                $sheet->mergeCells('A' . $totalRow . ':M' . $totalRow);
                $sheet->setCellValueExplicit('N' . $totalRow, $grandTotal, DataType::TYPE_NUMERIC);

                // Style total row
                $sheet->getStyle('A' . $totalRow . ':N' . $totalRow)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A' . $totalRow . ':N' . $totalRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('CCCCCC');
                $sheet->getStyle('A' . $totalRow . ':N' . $totalRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('N' . $totalRow)->getNumberFormat()
                    ->setFormatCode('"Rp. "#,##0');
                $sheet->getStyle('N' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(6);   // No
                $sheet->getColumnDimension('B')->setWidth(20);  // No Referensi
                $sheet->getColumnDimension('C')->setWidth(20);  // No Produksi
                $sheet->getColumnDimension('D')->setWidth(16);  // Tanggal
                $sheet->getColumnDimension('E')->setWidth(22);  // Tanggal Selesai
                $sheet->getColumnDimension('F')->setWidth(16);  // Branch
                $sheet->getColumnDimension('G')->setWidth(22);  // Pelanggan
                $sheet->getColumnDimension('H')->setWidth(60);  // Alamat Produksi
                $sheet->getColumnDimension('I')->setWidth(20);  // Team
                $sheet->getColumnDimension('J')->setWidth(35);  // Jenis Produksi
                $sheet->getColumnDimension('K')->setWidth(35);  // Nama Bahan
                $sheet->getColumnDimension('L')->setWidth(10);  // Qty
                $sheet->getColumnDimension('M')->setWidth(18);  // Harga
                $sheet->getColumnDimension('N')->setWidth(22);  // Total Harga

                // Set row heights for headers
                $sheet->getRowDimension($headerRow)->setRowHeight(25);
            },
        ];
    }
}

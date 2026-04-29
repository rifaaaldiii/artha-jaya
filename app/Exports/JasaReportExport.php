<?php

namespace App\Exports;

use App\Models\Jasa;
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

class JasaReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
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
        $query = Jasa::with(['pelanggan', 'petugasMany', 'items']);

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

    public function map($jasa): array
    {
        $this->rowNumber++;

        $itemsArray = [];
        if ($jasa->items && $jasa->items->count() > 0) {
            foreach ($jasa->items as $item) {
                $itemsArray[] = "{$item->nama_jasa} - {$item->jumlah} unit";
            }
        }

        $petugasNames = $jasa->petugasMany && $jasa->petugasMany->count() > 0 
            ? $jasa->petugasMany->pluck('nama')->join(', ') 
            : '-';

        return [
            $jasa->no_jasa,
            $jasa->jadwal_petugas ? $jasa->jadwal_petugas->format('d/m/Y H:i') : '-',
            $jasa->no_ref ?? '-',
            $jasa->pelanggan?->nama ?? '-',
            $petugasNames,
            implode("\n", $itemsArray),
            $jasa->items->count(),
            $jasa->items->sum('harga'),
            ucfirst($jasa->status),
            $jasa->catatan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function title(): string
    {
        return 'Laporan Jasa';
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
                $sheet->mergeCells('A1:M1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A2', 'Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117');
                $sheet->mergeCells('A2:M2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A3', 'Telp : (+62) 8777-4467-228 || Email : Info@arthajaya.com');
                $sheet->mergeCells('A3:M3');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Separator
                $sheet->getStyle('A4:M4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                // Report Title
                $sheet->setCellValue('A5', 'REKAP JASA & LAYANAN');
                $sheet->mergeCells('A5:M5');
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A5')->getFont();

                // Metadata
                $sheet->setCellValue('A6', 'Rentang Tanggal : ' . ($this->startDate ? \Carbon\Carbon::parse($this->startDate)->locale('id')->isoFormat('D MMMM YYYY') : 'Awal') . ' - ' . ($this->endDate ? \Carbon\Carbon::parse($this->endDate)->locale('id')->isoFormat('D MMMM YYYY') : 'Akhir'));
                $sheet->mergeCells('A6:M6');
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A6:A8')->getFont()->setSize(10);
                $sheet->getStyle('A6:A8')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

                // Headers Row
                $headerRow = 9;
                $headers = [
                    'A' => 'No.',
                    'B' => 'No. Referensi',
                    'C' => 'No. Jasa',
                    'D' => 'Tanggal',
                    'E' => 'Tanggal Selesai',
                    'F' => 'Branch',
                    'G' => 'Pelanggan',
                    'H' => 'Alamat Instalasi',
                    'I' => 'Petugas',
                    'J' => 'Detail Item',
                    'K' => 'Qty',
                    'L' => 'Harga',
                    'M' => 'Total Harga',
                ];

                foreach ($headers as $col => $header) {
                    $sheet->setCellValue($col . $headerRow, $header);
                }

                // Style headers
                $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F5F5F5');
                $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Data rows
                $dataStartRow = 10;
                $currentRow = $dataStartRow;
                $rowNumber = 1;
                $grandTotal = 0;

                foreach ($this->rows as $jasa) {
                    $petugasNames = $jasa->petugasMany && $jasa->petugasMany->count() > 0 
                        ? $jasa->petugasMany->pluck('nama')->join(', ') 
                        : '-';

                    // Calculate grand total for this jasa
                    $jasaTotalHarga = 0;
                    if ($jasa->items && $jasa->items->count() > 0) {
                        foreach ($jasa->items as $item) {
                            $qty = $item->jumlah ?? 0;
                            $harga = $item->harga ?? 0;
                            $jasaTotalHarga += ($qty * $harga);
                        }
                    }
                    $grandTotal += $jasaTotalHarga;

                    // If has items, create row for each item
                    if ($jasa->items && $jasa->items->count() > 0) {
                        $itemStartRow = $currentRow;
                        $itemIndex = 0;

                        foreach ($jasa->items as $item) {
                            $jenisLayanan = $item->jenis_layanan ?? $item->nama_jasa ?? 'Item';
                            $detailItem = $jenisLayanan . ($item->deskripsi ? " - {$item->deskripsi}" : '');
                            $qty = $item->jumlah ?? 0;
                            $harga = $item->harga ?? 0;
                            $totalHarga = $qty * $harga;

                            $sheet->setCellValue('A' . $currentRow, $itemIndex === 0 ? $rowNumber : '');
                            $sheet->setCellValue('B' . $currentRow, $itemIndex === 0 ? ($jasa->no_ref ?? '-') : '');
                            $sheet->setCellValue('C' . $currentRow, $itemIndex === 0 ? $jasa->no_jasa : '');
                            $sheet->setCellValue('D' . $currentRow, $itemIndex === 0 ? ($jasa->createdAt ? $jasa->createdAt->format('d/m/Y') : '-') : '');
                            $sheet->setCellValue('E' . $currentRow, $itemIndex === 0 ? ($jasa->updateAt ? $jasa->updateAt->format('d/m/Y') : '-') : '');
                            $sheet->setCellValue('F' . $currentRow, $itemIndex === 0 ? ($jasa->branch ?? '-') : '');
                            $sheet->setCellValue('G' . $currentRow, $itemIndex === 0 ? ($jasa->pelanggan?->nama ?? '-') : '');
                            $sheet->setCellValue('H' . $currentRow, $itemIndex === 0 ? ($jasa->alamat ?? $jasa->pelanggan?->alamat ?? '-') : '');
                            $sheet->setCellValue('I' . $currentRow, $itemIndex === 0 ? $petugasNames : '');
                            $sheet->setCellValue('J' . $currentRow, $detailItem);
                            $sheet->setCellValue('K' . $currentRow, $qty);
                            $sheet->setCellValueExplicit('L' . $currentRow, $harga, DataType::TYPE_NUMERIC);
                            $sheet->setCellValueExplicit('M' . $currentRow, $totalHarga, DataType::TYPE_NUMERIC);

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

                        // Style all rows for this jasa
                        for ($r = $itemStartRow; $r < $currentRow; $r++) {
                            $sheet->getStyle('A' . $r . ':M' . $r)->getBorders()->getAllBorders()
                                ->setBorderStyle(Border::BORDER_THIN);
                            $sheet->getStyle('A' . $r . ':M' . $r)->getAlignment()
                                ->setVertical(Alignment::VERTICAL_TOP);
                            
                            // Format currency
                            $sheet->getStyle('L' . $r)->getNumberFormat()
                                ->setFormatCode('"Rp. "#,##0');
                            $sheet->getStyle('M' . $r)->getNumberFormat()
                                ->setFormatCode('"Rp. "#,##0');
                            
                            // Set wrap text for detail item and alamat
                            $sheet->getStyle('J' . $r)->getAlignment()->setWrapText(true);
                            $sheet->getStyle('H' . $r)->getAlignment()->setWrapText(true);
                            
                            // Center alignment for No, No. Referensi, No. Jasa, and Qty
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
                            $sheet->getStyle('K' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('F' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('L' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                            $sheet->getStyle('M' . $r)->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        }

                        $rowNumber++;
                    } else {
                        // If no items, create one row with dash
                        $sheet->setCellValue('A' . $currentRow, $rowNumber);
                        $sheet->setCellValue('B' . $currentRow, $jasa->no_ref ?? '-');
                        $sheet->setCellValue('C' . $currentRow, $jasa->no_jasa);
                        $sheet->setCellValue('D' . $currentRow, $jasa->createdAt ? $jasa->createdAt->format('d/m/Y') : '-');
                        $sheet->setCellValue('E' . $currentRow, $jasa->updateAt ? $jasa->updateAt->format('d/m/Y') : '-');
                        $sheet->setCellValue('F' . $currentRow, $jasa->branch ?? '-');
                        $sheet->setCellValue('G' . $currentRow, $jasa->pelanggan?->nama ?? '-');
                        $sheet->setCellValue('H' . $currentRow, $jasa->alamat ?? $jasa->pelanggan?->alamat ?? '-');
                        $sheet->setCellValue('I' . $currentRow, $petugasNames);
                        $sheet->setCellValue('J' . $currentRow, '-');
                        $sheet->setCellValue('K' . $currentRow, 0);
                        $sheet->setCellValueExplicit('L' . $currentRow, 0, DataType::TYPE_NUMERIC);
                        $sheet->setCellValueExplicit('M' . $currentRow, 0, DataType::TYPE_NUMERIC);

                        // Style data row
                        $sheet->getStyle('A' . $currentRow . ':M' . $currentRow)->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle('A' . $currentRow . ':M' . $currentRow)->getAlignment()
                            ->setVertical(Alignment::VERTICAL_TOP);
                        
                        // Format currency
                        $sheet->getStyle('L' . $currentRow)->getNumberFormat()
                            ->setFormatCode('"Rp. "#,##0');
                        $sheet->getStyle('M' . $currentRow)->getNumberFormat()
                            ->setFormatCode('"Rp. "#,##0');
                        
                        // Set wrap text for alamat
                        $sheet->getStyle('H' . $currentRow)->getAlignment()->setWrapText(true);
                        
                        // Center alignment for No, No. Referensi, No. Jasa, and Qty
                        $sheet->getStyle('A' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('B' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('C' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('K' . $currentRow)->getAlignment()
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
                $sheet->mergeCells('A' . $totalRow . ':L' . $totalRow);
                $sheet->setCellValueExplicit('M' . $totalRow, $grandTotal, DataType::TYPE_NUMERIC);

                // Style total row
                $sheet->getStyle('A' . $totalRow . ':M' . $totalRow)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A' . $totalRow . ':M' . $totalRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('CCCCCC');
                $sheet->getStyle('A' . $totalRow . ':M' . $totalRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('M' . $totalRow)->getNumberFormat()
                    ->setFormatCode('"Rp. "#,##0');
                $sheet->getStyle('M' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(6);   // No
                $sheet->getColumnDimension('B')->setWidth(18);  // No Referensi
                $sheet->getColumnDimension('C')->setWidth(18);  // No Jasa
                $sheet->getColumnDimension('D')->setWidth(16);  // Tanggal
                $sheet->getColumnDimension('E')->setWidth(22);  // Tanggal Selesai
                $sheet->getColumnDimension('F')->setWidth(16);  // Branch
                $sheet->getColumnDimension('G')->setWidth(20);  // Pelanggan
                $sheet->getColumnDimension('H')->setWidth(60);  // Alamat Instalasi
                $sheet->getColumnDimension('I')->setWidth(20);  // Petugas
                $sheet->getColumnDimension('J')->setWidth(50);  // Detail Item
                $sheet->getColumnDimension('K')->setWidth(10);  // Qty
                $sheet->getColumnDimension('L')->setWidth(18);  // Harga
                $sheet->getColumnDimension('M')->setWidth(22);  // Total Harga

                // Set row heights for headers
                $sheet->getRowDimension($headerRow)->setRowHeight(25);
            },
        ];
    }
}

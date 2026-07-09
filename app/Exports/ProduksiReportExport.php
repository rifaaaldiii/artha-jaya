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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ProduksiReportExport implements WithStyles, WithTitle, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $branch;
    protected $rows;
    protected array $selectedColumns = [];
    protected array $columns = [];

    public function __construct($startDate = null, $endDate = null, $branch = null, array $selectedColumns = [])
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->branch = $branch;
        $this->rows = $this->getData();
        $this->selectedColumns = $selectedColumns;
        $this->columns = $this->buildColumns();
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
                $lastCol = $this->columnLetter(count($this->columns));

                // Set default font
                $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

                // Header Section - Company Info
                $sheet->setCellValue('A1', 'PT. ARTHA JAYA MAS');
                $sheet->mergeCells('A1:' . $lastCol . '1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A2', 'Jl. Ciwaru Raya, No 24, Cipare, Serang, 42117');
                $sheet->mergeCells('A2:' . $lastCol . '2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A3', 'Telp : (+62) 8777-4467-228 || Email : Info@arthajaya.com');
                $sheet->mergeCells('A3:' . $lastCol . '3');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Separator
                $sheet->getStyle('A4:' . $lastCol . '4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
                
                // Report Title
                $sheet->setCellValue('A5', 'REKAP PRODUKSI');
                $sheet->mergeCells('A5:' . $lastCol . '5');
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A5')->getFont();

                // Metadata
                $sheet->setCellValue('A6', 'Rentang Tanggal : ' . ($this->startDate ? \Carbon\Carbon::parse($this->startDate)->locale('id')->isoFormat('D MMMM YYYY') : 'Awal') . ' - ' . ($this->endDate ? \Carbon\Carbon::parse($this->endDate)->locale('id')->isoFormat('D MMMM YYYY') : 'Akhir'));
                $sheet->mergeCells('A6:' . $lastCol . '6');
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A6:A8')->getFont()->setSize(10);
                $sheet->getStyle('A6:A8')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

                // Headers Row
                $headerRow = 9;
                foreach ($this->columns as $idx => $column) {
                    $sheet->setCellValue($this->columnLetter($idx + 1) . $headerRow, $column['label']);
                }

                // Style headers
                $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F5F5F5');
                $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)->getBorders()->getAllBorders()
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
                            $harga = $qty > 0 ? ($item->harga / $qty) : 0;
                            $produksiTotalHarga += ($qty * $harga);
                        }
                    }
                    $grandTotal += $produksiTotalHarga;

                    $items = ($produksi->items && $produksi->items->count() > 0) ? $produksi->items : collect([null]);
                    $itemStartRow = $currentRow;

                    foreach ($items as $itemIndex => $item) {
                        $qty = $item ? ($item->jumlah ?? 0) : 0;
                        $harga = $qty > 0 ? (($item->harga ?? 0) / $qty) : 0;
                        $totalHarga = $qty * $harga;
                        $rowData = [
                            'no' => $itemIndex === 0 ? $rowNumber : '',
                            'no_ref' => $itemIndex === 0 ? ($produksi->no_ref ?? '-') : '',
                            'no_produksi' => $itemIndex === 0 ? ($produksi->no_produksi ?? '-') : '',
                            'tanggal' => $itemIndex === 0 ? ($produksi->createdAt ? $produksi->createdAt->format('d/m/Y') : '-') : '',
                            'tanggal_selesai' => $itemIndex === 0 ? ($produksi->updateAt ? $produksi->updateAt->format('d/m/Y') : '-') : '',
                            'branch' => $itemIndex === 0 ? ($produksi->branch ?? '-') : '',
                            'pelanggan' => $itemIndex === 0 ? ($produksi->pelanggan?->nama ?? '-') : '',
                            'alamat' => $itemIndex === 0 ? ($produksi->alamat ?? $produksi->pelanggan?->alamat ?? '-') : '',
                            'team' => $itemIndex === 0 ? $teamName : '',
                            'jenis_produksi' => $item?->nama_produksi ?? '-',
                            'nama_bahan' => $item?->nama_bahan ?? '-',
                            'catatan' => $itemIndex === 0 ? ($produksi->catatan ?? '-') : '',
                            'qty' => $qty,
                            'harga' => $harga,
                            'total_harga' => $totalHarga,
                        ];

                        foreach ($this->columns as $idx => $column) {
                            $colLetter = $this->columnLetter($idx + 1);
                            $value = $rowData[$column['key']] ?? '';
                            if (in_array($column['key'], ['harga', 'total_harga'], true)) {
                                $sheet->setCellValueExplicit($colLetter . $currentRow, $value, DataType::TYPE_NUMERIC);
                            } else {
                                $sheet->setCellValue($colLetter . $currentRow, $value);
                            }
                        }

                        $currentRow++;
                    }

                    $itemCount = $items->count();
                    if ($itemCount > 1) {
                        $sharedKeys = ['no', 'no_ref', 'no_produksi', 'tanggal', 'tanggal_selesai', 'branch', 'pelanggan', 'alamat', 'team', 'catatan'];
                        foreach ($this->columns as $idx => $column) {
                            if (in_array($column['key'], $sharedKeys, true)) {
                                $col = $this->columnLetter($idx + 1);
                                $sheet->mergeCells($col . $itemStartRow . ':' . $col . ($currentRow - 1));
                            }
                        }
                    }

                    for ($r = $itemStartRow; $r < $currentRow; $r++) {
                        $sheet->getStyle('A' . $r . ':' . $lastCol . $r)->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle('A' . $r . ':' . $lastCol . $r)->getAlignment()
                            ->setVertical(Alignment::VERTICAL_TOP);

                        foreach ($this->columns as $idx => $column) {
                            $col = $this->columnLetter($idx + 1);
                            if (($column['is_currency'] ?? false) === true) {
                                $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode('"Rp. "#,##0');
                            }
                            if (($column['wrap'] ?? false) === true) {
                                $sheet->getStyle($col . $r)->getAlignment()->setWrapText(true);
                            }
                            $sheet->getStyle($col . $r)->getAlignment()->setHorizontal(
                                $column['align'] ?? Alignment::HORIZONTAL_LEFT
                            );
                        }
                    }

                    $rowNumber++;
                }

                // Grand Total Row
                $totalRow = $currentRow;
                $totalColIndex = $this->findColumnIndex('total_harga');
                if ($totalColIndex !== null && $totalColIndex > 1) {
                    $sheet->setCellValue('A' . $totalRow, 'GRAND TOTAL');
                    $sheet->mergeCells('A' . $totalRow . ':' . $this->columnLetter($totalColIndex - 1) . $totalRow);
                    $sheet->setCellValueExplicit($this->columnLetter($totalColIndex) . $totalRow, $grandTotal, DataType::TYPE_NUMERIC);
                } else {
                    $sheet->setCellValue('A' . $totalRow, 'GRAND TOTAL');
                }

                // Style total row
                $sheet->getStyle('A' . $totalRow . ':' . $lastCol . $totalRow)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A' . $totalRow . ':' . $lastCol . $totalRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('CCCCCC');
                $sheet->getStyle('A' . $totalRow . ':' . $lastCol . $totalRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                if ($totalColIndex !== null) {
                    $totalCol = $this->columnLetter($totalColIndex);
                    $sheet->getStyle($totalCol . $totalRow)->getNumberFormat()->setFormatCode('"Rp. "#,##0');
                    $sheet->getStyle($totalCol . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Set column widths
                foreach ($this->columns as $idx => $column) {
                    $sheet->getColumnDimension($this->columnLetter($idx + 1))->setWidth($column['width']);
                }

                // Set row heights for headers
                $sheet->getRowDimension($headerRow)->setRowHeight(25);
            },
        ];
    }

    protected function buildColumns(): array
    {
        $available = [
            'no' => ['label' => 'No.', 'width' => 6, 'align' => Alignment::HORIZONTAL_CENTER],
            'no_ref' => ['label' => 'No. Referensi', 'width' => 20, 'align' => Alignment::HORIZONTAL_CENTER],
            'no_produksi' => ['label' => 'No. Produksi', 'width' => 20, 'align' => Alignment::HORIZONTAL_CENTER],
            'tanggal' => ['label' => 'Tanggal', 'width' => 16, 'align' => Alignment::HORIZONTAL_CENTER],
            'tanggal_selesai' => ['label' => 'Tanggal Selesai', 'width' => 22, 'align' => Alignment::HORIZONTAL_CENTER],
            'branch' => ['label' => 'Branch', 'width' => 16, 'align' => Alignment::HORIZONTAL_CENTER],
            'pelanggan' => ['label' => 'Pelanggan', 'width' => 22, 'align' => Alignment::HORIZONTAL_LEFT],
            'alamat' => ['label' => 'Alamat Produksi', 'width' => 60, 'align' => Alignment::HORIZONTAL_LEFT, 'wrap' => true],
            'team' => ['label' => 'Team', 'width' => 20, 'align' => Alignment::HORIZONTAL_CENTER],
            'jenis_produksi' => ['label' => 'Jenis Produksi', 'width' => 35, 'align' => Alignment::HORIZONTAL_LEFT, 'wrap' => true],
            'nama_bahan' => ['label' => 'Nama Bahan', 'width' => 35, 'align' => Alignment::HORIZONTAL_LEFT, 'wrap' => true],
            'catatan' => ['label' => 'Catatan', 'width' => 35, 'align' => Alignment::HORIZONTAL_LEFT, 'wrap' => true],
            'qty' => ['label' => 'Qty', 'width' => 10, 'align' => Alignment::HORIZONTAL_CENTER],
            'harga' => ['label' => 'Harga', 'width' => 18, 'align' => Alignment::HORIZONTAL_LEFT, 'is_currency' => true],
            'total_harga' => ['label' => 'Total Harga', 'width' => 22, 'align' => Alignment::HORIZONTAL_LEFT, 'is_currency' => true],
        ];

        $selected = empty($this->selectedColumns) ? array_keys($available) : $this->selectedColumns;
        $columns = [];
        foreach ($selected as $key) {
            if (!isset($available[$key])) {
                continue;
            }
            $columns[] = ['key' => $key] + $available[$key];
        }

        if (empty($columns)) {
            $columns[] = ['key' => 'no'] + $available['no'];
        }

        return $columns;
    }

    protected function columnLetter(int $index): string
    {
        return Coordinate::stringFromColumnIndex($index);
    }

    protected function findColumnIndex(string $key): ?int
    {
        foreach ($this->columns as $index => $column) {
            if ($column['key'] === $key) {
                return $index + 1;
            }
        }

        return null;
    }
}

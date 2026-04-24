<?php

namespace App\Exports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProduksiReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $singleNumber;
    protected $startDate;
    protected $endDate;

    public function __construct($singleNumber = null, $startDate = null, $endDate = null)
    {
        $this->singleNumber = $singleNumber;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Produksi::with(['team', 'items', 'pelanggan']);

        if ($this->singleNumber) {
            $query->where('no_produksi', $this->singleNumber);
        } else {
            if ($this->startDate) {
                $query->whereDate('createdAt', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('createdAt', '<=', $this->endDate);
            }
        }

        return $query->orderBy('createdAt', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No.',
            'No. Produksi',
            'No. Ref',
            'Tanggal',
            'Branch',
            'Team',
            'Customer',
            'Kontak',
            'Detail Item',
            'Jumlah Item',
            'Total Harga',
            'Status',
            'Catatan',
        ];
    }

    public function map($produksi): array
    {
        static $index = 0;
        $index++;

        $itemsSummary = '';
        if ($produksi->items && $produksi->items->count() > 0) {
            $itemsArray = [];
            foreach ($produksi->items as $item) {
                $itemsArray[] = "{$item->nama_produksi} ({$item->nama_bahan}) - {$item->jumlah} unit";
            }
            $itemsSummary = implode("\n", $itemsArray);
        }

        return [
            $index,
            $produksi->no_produksi,
            $produksi->no_ref ?? '-',
            $produksi->createdAt ? $produksi->createdAt->format('d/m/Y H:i') : '-',
            $produksi->branch ?? '-',
            $produksi->team?->nama ?? '-',
            $produksi->pelanggan?->nama ?? '-',
            $produksi->pelanggan?->kontak ?? '-',
            $itemsSummary,
            $produksi->items->count(),
            $produksi->items->sum('harga'),
            ucfirst($produksi->status),
            $produksi->catatan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E8F5E9',
                    ],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Produksi';
    }
}

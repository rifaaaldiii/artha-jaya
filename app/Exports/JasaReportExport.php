<?php

namespace App\Exports;

use App\Models\Jasa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JasaReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
        $query = Jasa::with(['pelanggan', 'petugasMany', 'items']);

        if ($this->singleNumber) {
            $query->where('no_jasa', $this->singleNumber);
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
            'No. Jasa',
            'No. Ref',
            'Jadwal Customer',
            'Jadwal Petugas',
            'Branch',
            'Pelanggan',
            'Kontak',
            'Alamat',
            'Petugas',
            'Detail Item',
            'Jumlah Item',
            'Total Harga',
            'Status',
            'Catatan',
        ];
    }

    public function map($jasa): array
    {
        static $index = 0;
        $index++;

        $petugasNames = '';
        if ($jasa->petugasMany && $jasa->petugasMany->count() > 0) {
            $petugasNames = $jasa->petugasMany->pluck('nama')->join(', ');
        }

        $itemsSummary = '';
        if ($jasa->items && $jasa->items->count() > 0) {
            $itemsArray = [];
            foreach ($jasa->items as $item) {
                $itemsArray[] = "{$item->jenis_layanan} - {$item->jumlah} unit";
            }
            $itemsSummary = implode("\n", $itemsArray);
        }

        return [
            $index,
            $jasa->no_jasa,
            $jasa->no_ref ?? '-',
            $jasa->jadwal ? $jasa->jadwal->format('d/m/Y H:i') : '-',
            $jasa->jadwal_petugas ? $jasa->jadwal_petugas->format('d/m/Y H:i') : '-',
            $jasa->branch ?? '-',
            $jasa->pelanggan?->nama ?? '-',
            $jasa->pelanggan?->kontak ?? '-',
            $jasa->alamat ?? $jasa->pelanggan?->alamat ?? '-',
            $petugasNames ?: '-',
            $itemsSummary,
            $jasa->items->count(),
            $jasa->items->sum('harga'),
            ucfirst($jasa->status),
            $jasa->catatan ?? '-',
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
        return 'Laporan Jasa';
    }
}

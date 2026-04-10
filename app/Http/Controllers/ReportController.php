<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Jasa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $reportType = $request->query('report_type', 'produksi');
        $singleNumber = $request->query('single_number');
        $format = $request->query('format', 'report');
        $preview = $request->query('preview', false);
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // If single number is provided, generate single report
        if ($singleNumber) {
            $reportData = $this->loadReportData($reportType, $singleNumber);

            if (!$reportData) {
                abort(404, 'Report data not found');
            }

            $viewPath = $format === 'invoice' 
                ? "reports/pdf/{$reportType}-invoice"
                : "reports/pdf/{$reportType}";

            $data = [
                'row' => $reportData,
                'generatedAt' => now(),
                'filters' => [],
                'summary' => [
                    'total' => 1,
                    'date_range' => now()->format('d/m/Y'),
                ],
                'rows' => [$reportData],
            ];

            $pdf = Pdf::loadView($viewPath, $data);

            $filename = $format === 'invoice'
                ? "{$reportType}-invoice-{$singleNumber}.pdf"
                : "{$reportType}-{$singleNumber}.pdf";

            if ($preview) {
                return $pdf->stream($filename);
            }

            return $pdf->download($filename);
        }

        // Otherwise, generate report for date range
        $query = $reportType === 'produksi' 
            ? \App\Models\Produksi::with(['team', 'items'])
            : \App\Models\Jasa::with(['pelanggan', 'petugas', 'items']);

        if (!empty($startDate)) {
            $query->whereDate('createdAt', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('createdAt', '<=', $endDate);
        }

        $items = $query->orderBy('createdAt', 'desc')->get();
        
        $viewPath = $format === 'invoice'
            ? "reports/pdf/{$reportType}-invoice"
            : "reports/pdf/{$reportType}";
            
        $data = [
            'rows' => $items->map(function ($item) use ($reportType) {
                if ($reportType === 'produksi') {
                    return [
                        'number' => $item->no_produksi,
                        'no_ref' => $item->no_ref ?? '-',
                        'team' => $item->team?->nama ?? '-',
                        'total_harga' => $item->items->sum('harga'),
                        'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                    ];
                } else {
                    return [
                        'number' => $item->no_jasa,
                        'no_ref' => $item->no_ref ?? '-',
                        'customer' => $item->pelanggan?->nama ?? '-',
                        'total_harga' => $item->items->sum('harga'),
                        'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                    ];
                }
            })->toArray(),
            'summary' => [
                'total' => $items->count(),
                'date_range' => ($startDate ?? 'Awal') . ' - ' . ($endDate ?? 'Akhir'),
            ],
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView($viewPath, $data);
        
        $filename = $format === 'invoice'
            ? "invoice-{$reportType}-" . now()->format('Y-m-d') . ".pdf"
            : "report-{$reportType}-" . now()->format('Y-m-d') . ".pdf";

        if ($preview) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    protected function loadReportData($reportType, $singleNumber)
    {
        if ($reportType === 'produksi') {
            $produksi = Produksi::with(['team', 'items'])->where('no_produksi', $singleNumber)->first();
            
            if (!$produksi) {
                return null;
            }

            return [
                'number' => $produksi->no_produksi,
                'no_ref' => $produksi->no_ref,
                'branch' => $produksi->branch,
                'status' => $produksi->status,
                'team' => $produksi->team?->nama ?? '-',
                'catatan' => $produksi->catatan,
                'created_at' => $produksi->createdAt?->format('d/m/Y H:i') ?? '-',
                'updated_at' => $produksi->updateAt?->format('d/m/Y H:i') ?? '-',
                'items_count' => $produksi->items->count(),
                'total_harga' => $produksi->items->sum('harga'),
                'items' => $produksi->items->map(function ($item) {
                    return [
                        'nama_produksi' => $item->nama_produksi,
                        'nama_bahan' => $item->nama_bahan,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                    ];
                })->toArray(),
                'note' => $produksi->catatan,
            ];
        } elseif ($reportType === 'jasa') {
            $jasa = Jasa::with(['pelanggan', 'petugas', 'items'])->where('no_jasa', $singleNumber)->first();
            
            if (!$jasa) {
                return null;
            }

            return [
                'number' => $jasa->no_jasa,
                'no_ref' => $jasa->no_ref,
                'branch' => $jasa->branch,
                'status' => $jasa->status,
                'pelanggan' => $jasa->pelanggan?->nama ?? '-',
                'petugas' => $jasa->petugas->pluck('nama')->join(', ') ?: '-',
                'scheduled_at' => $jasa->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                'catatan' => $jasa->catatan,
                'created_at' => $jasa->createdAt?->format('d/m/Y H:i') ?? '-',
                'updated_at' => $jasa->updateAt?->format('d/m/Y H:i') ?? '-',
                'items_count' => $jasa->items->count(),
                'total_harga' => $jasa->items->sum('harga'),
                'items' => $jasa->items->map(function ($item) {
                    return [
                        'jenis_layanan' => $item->nama_jasa,
                        'nama_jasa' => $item->nama_jasa,
                        'deskripsi' => $item->deskripsi ?? '-',
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                    ];
                })->toArray(),
                'note' => $jasa->catatan,
            ];
        }

        return null;
    }
}

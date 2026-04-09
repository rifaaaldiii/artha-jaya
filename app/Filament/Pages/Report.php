<?php

namespace App\Filament\Pages;

use App\Models\Produksi;
use App\Models\Jasa;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;

class Report extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.pages.report';
    protected static ?string $title = 'Report Center';
    protected static ?string $navigationLabel = 'Report';
    protected static ?int $navigationSort = 10;

    public ?string $reportType = 'jasa';
    public ?string $singleNumber = null;
    public ?string $format = 'report'; // 'report' or 'invoice'
    public ?array $reportData = null;
    public ?string $pdfUrl = null;

    // Properties for list view
    public array $filters = [
        'report_type' => 'jasa',
        'start_date' => null,
        'end_date' => null,
    ];
    
    public array $previewRows = [];
    public int $resultCount = 0;
    public int $currentPage = 1;
    public int $perPage = 10;
    public array $downloadingNumbers = [];

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'admin_gudang'], true);
    }

    public function mount(Request $request): void
    {
        $this->reportType = $request->query('report_type', 'jasa');
        $this->singleNumber = $request->query('single_number');
        $this->format = $request->query('format', 'report');

        if ($this->singleNumber) {
            $this->loadReportData();
        } else {
            $this->filters['report_type'] = $this->reportType;
            $this->loadPreviewData();
        }

        $this->filterForm->fill($this->filters);
    }

    protected function loadReportData(): void
    {
        if ($this->reportType === 'produksi') {
            $produksi = Produksi::with(['team', 'items'])->where('no_produksi', $this->singleNumber)->first();
            
            if ($produksi) {
                $this->reportData = [
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
            }
        } elseif ($this->reportType === 'jasa') {
            $jasa = Jasa::with(['pelanggan', 'petugas', 'items'])->where('no_jasa', $this->singleNumber)->first();
            
            if ($jasa) {
                $this->reportData = [
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
        }
    }

    protected function loadPreviewData(): void
    {
        $query = $this->filters['report_type'] === 'produksi' 
            ? Produksi::with(['team', 'items'])
            : Jasa::with(['pelanggan', 'petugas', 'items']);

        // Apply date filters
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('createdAt', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('createdAt', '<=', $this->filters['end_date']);
        }

        // Get total count
        $this->resultCount = $query->count();

        // Get paginated data
        $offset = ($this->currentPage - 1) * $this->perPage;
        $items = $query->orderBy('createdAt', 'desc')->skip($offset)->take($this->perPage)->get();

        $this->previewRows = $items->map(function ($item) {
            if ($this->filters['report_type'] === 'produksi') {
                return [
                    'number' => $item->no_produksi,
                    'no_ref' => $item->no_ref ?? '-',
                    'items_summary' => $item->items->map(function ($i) {
                        return "{$i->nama_produksi} - {$i->nama_bahan} ({$i->jumlah})";
                    })->join("\n"),
                    'items_count' => $item->items->count(),
                    'total_harga' => $item->items->sum('harga'),
                    'team' => $item->team?->nama ?? '-',
                    'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                    'note' => $item->catatan,
                ];
            } else {
                return [
                    'number' => $item->no_jasa,
                    'no_ref' => $item->no_ref ?? '-',
                    'items_summary' => $item->items->map(function ($i) {
                        return "{$i->nama_jasa} ({$i->jumlah})";
                    })->join("\n"),
                    'items_count' => $item->items->count(),
                    'total_harga' => $item->items->sum('harga'),
                    'customer' => $item->pelanggan?->nama ?? '-',
                    'petugas' => $item->petugas->pluck('nama')->join(', ') ?: '-',
                    'scheduled_at' => $item->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                    'note' => $item->catatan,
                ];
            }
        })->toArray();
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Tipe Laporan')
                    ->options([
                        'produksi' => 'Produksi',
                        'jasa' => 'Jasa',
                    ])
                    ->default('jasa')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['report_type'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    }),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->native(false),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->native(false),
            ])
            ->statePath('filters');
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'report_type' => 'jasa',
            'start_date' => null,
            'end_date' => null,
        ];
        $this->currentPage = 1;
        $this->loadPreviewData();
        $this->filterForm->fill($this->filters);
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadPreviewData();
        }
    }

    public function nextPage(): void
    {
        $totalPages = (int) ceil($this->resultCount / $this->perPage);
        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadPreviewData();
        }
    }

    public function goToPage(int $page): void
    {
        $this->currentPage = $page;
        $this->loadPreviewData();
    }

    public function downloadPdfByDateRange()
    {
        $query = $this->filters['report_type'] === 'produksi' 
            ? Produksi::with(['team', 'items'])
            : Jasa::with(['pelanggan', 'petugas', 'items']);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('createdAt', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('createdAt', '<=', $this->filters['end_date']);
        }

        $items = $query->orderBy('createdAt', 'desc')->get();
        
        $viewPath = "reports/pdf/{$this->filters['report_type']}";
        $data = [
            'rows' => $items->map(function ($item) {
                if ($this->filters['report_type'] === 'produksi') {
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
                'date_range' => ($this->filters['start_date'] ?? 'Awal') . ' - ' . ($this->filters['end_date'] ?? 'Akhir'),
            ],
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView($viewPath, $data);
        
        $filename = "report-{$this->filters['report_type']}-" . now()->format('Y-m-d') . ".pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadInvoiceByDateRange()
    {
        $query = $this->filters['report_type'] === 'produksi' 
            ? Produksi::with(['team', 'items'])
            : Jasa::with(['pelanggan', 'petugas', 'items']);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('createdAt', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('createdAt', '<=', $this->filters['end_date']);
        }

        $items = $query->orderBy('createdAt', 'desc')->get();
        
        $viewPath = "reports/pdf/{$this->filters['report_type']}-invoice";
        $data = [
            'rows' => $items->map(function ($item) {
                if ($this->filters['report_type'] === 'produksi') {
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
                'date_range' => ($this->filters['start_date'] ?? 'Awal') . ' - ' . ($this->filters['end_date'] ?? 'Akhir'),
            ],
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView($viewPath, $data);
        
        $filename = "invoice-{$this->filters['report_type']}-" . now()->format('Y-m-d') . ".pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadPdfSingleByNumber(string $number)
    {
        $this->downloadingNumbers[$number] = true;
        
        if ($this->filters['report_type'] === 'produksi') {
            $produksi = Produksi::with(['team', 'items'])->where('no_produksi', $number)->first();
            if (!$produksi) return;
            
            $data = [
                'row' => [
                    'number' => $produksi->no_produksi,
                    'no_ref' => $produksi->no_ref,
                    'branch' => $produksi->branch,
                    'status' => $produksi->status,
                    'team' => $produksi->team?->nama ?? '-',
                    'catatan' => $produksi->catatan,
                    'created_at' => $produksi->createdAt?->format('d/m/Y H:i') ?? '-',
                    'items_count' => $produksi->items->count(),
                    'total_harga' => $produksi->items->sum('harga'),
                    'items' => $produksi->items->toArray(),
                    'note' => $produksi->catatan,
                ],
                'generatedAt' => now(),
            ];

            $pdf = Pdf::loadView('reports/pdf/produksi', $data);
            $filename = "produksi-{$number}.pdf";
        } else {
            $jasa = Jasa::with(['pelanggan', 'petugas', 'items'])->where('no_jasa', $number)->first();
            if (!$jasa) return;
            
            $data = [
                'row' => [
                    'number' => $jasa->no_jasa,
                    'no_ref' => $jasa->no_ref,
                    'branch' => $jasa->branch,
                    'status' => $jasa->status,
                    'pelanggan' => $jasa->pelanggan?->nama ?? '-',
                    'petugas' => $jasa->petugas->pluck('nama')->join(', ') ?: '-',
                    'scheduled_at' => $jasa->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                    'catatan' => $jasa->catatan,
                    'created_at' => $jasa->createdAt?->format('d/m/Y H:i') ?? '-',
                    'items_count' => $jasa->items->count(),
                    'total_harga' => $jasa->items->sum('harga'),
                    'items' => $jasa->items->toArray(),
                    'note' => $jasa->catatan,
                ],
                'generatedAt' => now(),
            ];

            $pdf = Pdf::loadView('reports/pdf/jasa', $data);
            $filename = "jasa-{$number}.pdf";
        }

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadInvoiceSingleByNumber(string $number)
    {
        $this->downloadingNumbers[$number . '_invoice'] = true;
        
        if ($this->filters['report_type'] === 'produksi') {
            $produksi = Produksi::with(['team', 'items'])->where('no_produksi', $number)->first();
            if (!$produksi) return;
            
            $data = [
                'row' => [
                    'number' => $produksi->no_produksi,
                    'no_ref' => $produksi->no_ref,
                    'branch' => $produksi->branch,
                    'status' => $produksi->status,
                    'team' => $produksi->team?->nama ?? '-',
                    'catatan' => $produksi->catatan,
                    'created_at' => $produksi->createdAt?->format('d/m/Y H:i') ?? '-',
                    'items_count' => $produksi->items->count(),
                    'total_harga' => $produksi->items->sum('harga'),
                    'items' => $produksi->items->toArray(),
                    'note' => $produksi->catatan,
                ],
                'generatedAt' => now(),
            ];

            $pdf = Pdf::loadView('reports/pdf/produksi-invoice', $data);
            $filename = "invoice-produksi-{$number}.pdf";
        } else {
            $jasa = Jasa::with(['pelanggan', 'petugas', 'items'])->where('no_jasa', $number)->first();
            if (!$jasa) return;
            
            $data = [
                'row' => [
                    'number' => $jasa->no_jasa,
                    'no_ref' => $jasa->no_ref,
                    'branch' => $jasa->branch,
                    'status' => $jasa->status,
                    'pelanggan' => $jasa->pelanggan?->nama ?? '-',
                    'petugas' => $jasa->petugas->pluck('nama')->join(', ') ?: '-',
                    'scheduled_at' => $jasa->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                    'catatan' => $jasa->catatan,
                    'created_at' => $jasa->createdAt?->format('d/m/Y H:i') ?? '-',
                    'items_count' => $jasa->items->count(),
                    'total_harga' => $jasa->items->sum('harga'),
                    'items' => $jasa->items->toArray(),
                    'note' => $jasa->catatan,
                ],
                'generatedAt' => now(),
            ];

            $pdf = Pdf::loadView('reports/pdf/jasa-invoice', $data);
            $filename = "invoice-jasa-{$number}.pdf";
        }

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function generatePdf()
    {
        if (!$this->reportData) {
            return;
        }

        $viewPath = $this->format === 'invoice' 
            ? "reports/pdf/{$this->reportType}-invoice"
            : "reports/pdf/{$this->reportType}";

        $data = [
            'row' => $this->reportData,
            'generatedAt' => now(),
            'filters' => [],
            'summary' => [
                'total' => 1,
                'date_range' => now()->format('d/m/Y'),
            ],
            'rows' => [$this->reportData],
        ];

        $pdf = Pdf::loadView($viewPath, $data);
        
        $filename = $this->format === 'invoice'
            ? "{$this->reportType}-invoice-{$this->singleNumber}.pdf"
            : "{$this->reportType}-{$this->singleNumber}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function previewPdf()
    {
        if (!$this->reportData) {
            return;
        }

        $viewPath = $this->format === 'invoice' 
            ? "reports/pdf/{$this->reportType}-invoice"
            : "reports/pdf/{$this->reportType}";

        $data = [
            'row' => $this->reportData,
            'generatedAt' => now(),
            'filters' => [],
            'summary' => [
                'total' => 1,
                'date_range' => now()->format('d/m/Y'),
            ],
            'rows' => [$this->reportData],
        ];

        $pdf = Pdf::loadView($viewPath, $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'preview.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
        ]);
    }

    public function getReportTitle(): string
    {
        if ($this->format === 'invoice') {
            return $this->reportType === 'produksi' ? 'Invoice Produksi' : 'Invoice Jasa';
        }
        
        return $this->reportType === 'produksi' ? 'Laporan Produksi' : 'Laporan Jasa';
    }

    public function getReportSubtitle(): string
    {
        return $this->singleNumber ?? '-';
    }
}

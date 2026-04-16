<?php

namespace App\Filament\Pages;

use App\Models\Produksi;
use App\Models\Jasa;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

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
    public string $searchQuery = '';

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    protected function getForms(): array
    {
        return [
            'filterForm',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['administrator', 'admin_toko', 'superadmin'], true);
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
            $produksi = Produksi::with(['items'])->where('no_produksi', $this->singleNumber)->first();

            if ($produksi) {
                $this->reportData = [
                    'number' => $produksi->no_produksi,
                    'no_ref' => $produksi->no_ref,
                    'branch' => $produksi->branch,
                    'status' => $produksi->status,
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
        // Security: Prevent superadmin from accessing Jasa reports
        $user = Auth::user();
        if ($user && $user->role === 'superadmin' && $this->filters['report_type'] === 'jasa') {
            Notification::make()
                ->title('Access Denied')
                ->body('You do not have permission to view Jasa reports.')
                ->danger()
                ->send();
            
            $this->filters['report_type'] = 'produksi';
        }

        $query = $this->filters['report_type'] === 'produksi' 
            ? Produksi::with(['team', 'items'])
            : Jasa::with(['pelanggan', 'petugasMany', 'items']);

        // Apply date filters
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('createdAt', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('createdAt', '<=', $this->filters['end_date']);
        }

        // Apply search filter
        if (!empty($this->searchQuery)) {
            $searchTerm = '%' . $this->searchQuery . '%';
            
            if ($this->filters['report_type'] === 'produksi') {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('no_produksi', 'like', $searchTerm)
                      ->orWhere('no_ref', 'like', $searchTerm)
                      ->orWhere('branch', 'like', $searchTerm)
                      ->orWhere('status', 'like', $searchTerm);
                });
            } else {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('no_jasa', 'like', $searchTerm)
                      ->orWhere('no_ref', 'like', $searchTerm)
                      ->orWhere('status', 'like', $searchTerm)
                      ->orWhereHas('pelanggan', function($q) use ($searchTerm) {
                          $q->where('nama', 'like', $searchTerm);
                      })
                      ->orWhereHas('petugasMany', function($q) use ($searchTerm) {
                          $q->where('nama', 'like', $searchTerm);
                      });
                });
            }
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
                    'branch' => $item->branch ?? '-',
                    'team' => $item->team?->nama ?? '-',
                    'status' => $item->status,
                    'items_count' => $item->items->count(),
                    'total_harga' => $item->items->sum('harga'),
                    'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                ];
            } else {
                return [
                    'number' => $item->no_jasa,
                    'no_ref' => $item->no_ref ?? '-',
                    'customer' => $item->pelanggan?->nama ?? '-',
                    'petugas' => $item->petugasMany->isNotEmpty() 
                        ? $item->petugasMany->pluck('nama')->join(', ') 
                        : '-',
                    'status' => $item->status,
                    'scheduled_at' => $item->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                    'items_count' => $item->items->count(),
                    'total_harga' => $item->items->sum('harga'),
                    'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                ];
            }
        })->toArray();
    }

    public function filterForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Jenis Laporan')
                    ->options(function () {
                        $user = Auth::user();
                        
                        // superadmin hanya bisa melihat Produksi
                        if ($user && $user->role === 'superadmin') {
                            return [
                                'produksi' => 'Produksi',
                            ];
                        }
                        
                        // Users lain bisa melihat semua
                        return [
                            'produksi' => 'Produksi',
                            'jasa' => 'Jasa',
                        ];
                    })
                    ->default(function () {
                        $user = Auth::user();
                        
                        // superadmin default ke produksi
                        if ($user && $user->role === 'superadmin') {
                            return 'produksi';
                        }
                        
                        return 'produksi';
                    })
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['report_type'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    })
                    ->placeholder('Pilih jenis laporan'),
                
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['start_date'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    })
                    ->placeholder('Pilih tanggal mulai'),
                
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['end_date'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    })
                    ->placeholder('Pilih tanggal akhir'),
            ])
            ->columns(3)
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

    public function applyFilters(): void
    {
        $this->currentPage = 1;
        $this->loadPreviewData();
    }

    public function openInvoice(string $number, ?string $type = null)
    {
        // Gunakan type dari parameter atau dari filters
        $reportType = $type ?? $this->filters['report_type'];
        
        // Generate invoice PDF dan stream ke browser untuk preview
        if ($reportType === 'produksi') {
            $produksi = Produksi::with(['team', 'items'])->where('no_produksi', $number)->first();
            if (!$produksi) {
                Notification::make()
                    ->title('Data tidak ditemukan')
                    ->danger()
                    ->send();
                return;
            }

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
            $jasa = Jasa::with(['pelanggan', 'petugasMany', 'items'])->where('no_jasa', $number)->first();
            if (!$jasa) {
                Notification::make()
                    ->title('Data tidak ditemukan')
                    ->danger()
                    ->send();
                return;
            }

            $data = [
                'row' => [
                    'number' => $jasa->no_jasa,
                    'no_ref' => $jasa->no_ref,
                    'branch' => $jasa->branch,
                    'status' => $jasa->status,
                    'pelanggan' => $jasa->pelanggan?->nama ?? '-',
                    'petugas' => $jasa->petugasMany->isNotEmpty() 
                        ? $jasa->petugasMany->pluck('nama')->join(', ') 
                        : '-',
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

        // Stream PDF inline untuk preview di tab baru
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function previewInvoice(string $number, ?string $type = null)
    {
        // Gunakan type dari parameter atau dari filters
        $reportType = $type ?? $this->filters['report_type'];
        
        // Generate invoice PDF untuk preview inline
        if ($reportType === 'produksi') {
            $produksi = Produksi::with(['team', 'items'])->where('no_produksi', $number)->first();
            if (!$produksi) {
                Notification::make()
                    ->title('Data tidak ditemukan')
                    ->danger()
                    ->send();
                return;
            }

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
            $jasa = Jasa::with(['pelanggan', 'petugasMany', 'items'])->where('no_jasa', $number)->first();
            if (!$jasa) {
                Notification::make()
                    ->title('Data tidak ditemukan')
                    ->danger()
                    ->send();
                return;
            }

            $data = [
                'row' => [
                    'number' => $jasa->no_jasa,
                    'no_ref' => $jasa->no_ref,
                    'branch' => $jasa->branch,
                    'status' => $jasa->status,
                    'pelanggan' => $jasa->pelanggan?->nama ?? '-',
                    'petugas' => $jasa->petugasMany->isNotEmpty() 
                        ? $jasa->petugasMany->pluck('nama')->join(', ') 
                        : '-',
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

        // Output PDF inline untuk preview di browser
        return $pdf->stream($filename);
    }

    public function downloadFilteredPdf()
    {
        $query = $this->filters['report_type'] === 'produksi' 
            ? Produksi::with(['team', 'items'])
            : Jasa::with(['pelanggan', 'petugasMany', 'items']);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('createdAt', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('createdAt', '<=', $this->filters['end_date']);
        }

        // Apply search filter
        if (!empty($this->searchQuery)) {
            $searchTerm = '%' . $this->searchQuery . '%';
            
            if ($this->filters['report_type'] === 'produksi') {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('no_produksi', 'like', $searchTerm)
                      ->orWhere('no_ref', 'like', $searchTerm)
                      ->orWhere('branch', 'like', $searchTerm)
                      ->orWhere('status', 'like', $searchTerm);
                });
            } else {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('no_jasa', 'like', $searchTerm)
                      ->orWhere('no_ref', 'like', $searchTerm)
                      ->orWhere('status', 'like', $searchTerm)
                      ->orWhereHas('pelanggan', function($q) use ($searchTerm) {
                          $q->where('nama', 'like', $searchTerm);
                      })
                      ->orWhereHas('petugasMany', function($q) use ($searchTerm) {
                          $q->where('nama', 'like', $searchTerm);
                      });
                });
            }
        }

        $items = $query->orderBy('createdAt', 'desc')->get();

        $viewPath = "reports/pdf/{$this->filters['report_type']}";
        $data = [
            'rows' => $items->map(function ($item) {
                if ($this->filters['report_type'] === 'produksi') {
                    // Build items summary
                    $itemsSummary = '';
                    if ($item->items->isNotEmpty()) {
                        $itemsArray = [];
                        foreach ($item->items as $prodItem) {
                            $itemName = $prodItem->nama_produksi ?? $prodItem->nama_item ?? 'Item';
                            $itemsArray[] = "• {$itemName} ({$prodItem->jumlah})";
                        }
                        $itemsSummary = implode("\n", $itemsArray);
                    }
                    
                    return [
                        'number' => $item->no_produksi,
                        'no_ref' => $item->no_ref ?? '-',
                        'branch' => $item->branch ?? '-',
                        'team' => $item->team?->nama ?? '-',
                        'status' => $item->status,
                        'items_count' => $item->items->count(),
                        'total_harga' => $item->items->sum('harga'),
                        'items_summary' => $itemsSummary,
                        'note' => $item->catatan,
                        'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                    ];
                } else {
                    // Build items summary
                    $itemsSummary = '';
                    if ($item->items->isNotEmpty()) {
                        $itemsArray = [];
                        foreach ($item->items as $jasaItem) {
                            $itemName = $jasaItem->jenis_layanan ?? $jasaItem->nama_item ?? 'Item';
                            $itemsArray[] = "• {$itemName} ({$jasaItem->jumlah})";
                        }
                        $itemsSummary = implode("\n", $itemsArray);
                    }
                    
                    return [
                        'number' => $item->no_jasa,
                        'no_ref' => $item->no_ref ?? '-',
                        'customer' => $item->pelanggan?->nama ?? '-',
                        'petugas' => $item->petugasMany->isNotEmpty() 
                            ? $item->petugasMany->pluck('nama')->join(', ') 
                            : '-',
                        'status' => $item->status,
                        'scheduled_at' => $item->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                        'items_count' => $item->items->count(),
                        'total_harga' => $item->items->sum('harga'),
                        'items_summary' => $itemsSummary,
                        'note' => $item->catatan,
                        'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                    ];
                }
            })->toArray(),
            'summary' => [
                'total' => $items->count(),
                'total_harga' => $items->sum(function($item) { return $item->items->sum('harga'); }),
                'date_range' => ($this->filters['start_date'] ?? 'Awal') . ' - ' . ($this->filters['end_date'] ?? 'Akhir'),
            ],
            'filters' => $this->filters,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView($viewPath, $data);

        $filename = "report-{$this->filters['report_type']}-filtered-" . now()->format('Y-m-d') . ".pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
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
            : Jasa::with(['pelanggan', 'petugasMany', 'items']);

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
                    // Build items summary
                    $itemsSummary = '';
                    if ($item->items->isNotEmpty()) {
                        $itemsArray = [];
                        foreach ($item->items as $prodItem) {
                            $itemName = $prodItem->nama_produksi ?? $prodItem->nama_item ?? 'Item';
                            $itemsArray[] = "• {$itemName} ({$prodItem->jumlah})";
                        }
                        $itemsSummary = implode("\n", $itemsArray);
                    }
                    
                    return [
                        'number' => $item->no_produksi,
                        'no_ref' => $item->no_ref ?? '-',
                        'branch' => $item->branch ?? '-',
                        'status' => $item->status,
                        'team' => $item->team?->nama ?? '-',
                        'items_count' => $item->items->count(),
                        'total_harga' => $item->items->sum('harga'),
                        'items_summary' => $itemsSummary,
                        'note' => $item->catatan,
                        'created_at' => $item->createdAt?->format('d/m/Y H:i') ?? '-',
                    ];
                } else {
                    // Build items summary
                    $itemsSummary = '';
                    if ($item->items->isNotEmpty()) {
                        $itemsArray = [];
                        foreach ($item->items as $jasaItem) {
                            $itemName = $jasaItem->jenis_layanan ?? $jasaItem->nama_item ?? 'Item';
                            $itemsArray[] = "• {$itemName} ({$jasaItem->jumlah})";
                        }
                        $itemsSummary = implode("\n", $itemsArray);
                    }
                    
                    return [
                        'number' => $item->no_jasa,
                        'no_ref' => $item->no_ref ?? '-',
                        'branch' => $item->branch ?? '-',
                        'status' => $item->status,
                        'customer' => $item->pelanggan?->nama ?? '-',
                        'petugas' => $item->petugasMany->isNotEmpty() 
                            ? $item->petugasMany->pluck('nama')->join(', ') 
                            : '-',
                        'scheduled_at' => $item->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                        'items_count' => $item->items->count(),
                        'total_harga' => $item->items->sum('harga'),
                        'items_summary' => $itemsSummary,
                        'note' => $item->catatan,
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
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
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
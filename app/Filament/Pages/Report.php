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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class Report extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.pages.report';
    protected static ?string $title = 'Report Center';
    protected static ?string $navigationLabel = 'Report';
    protected static ?int $navigationSort = 10;

    public ?string $reportType = 'jasa';
    public ?string $singleNumber = null;
    public ?string $format = 'report';
    public ?array $reportData = null;
    public ?string $pdfUrl = null;

    public array $filters = [
        'report_type' => 'jasa',
        'start_date' => null,
        'end_date' => null,
    ];
    
    public array $previewRows = [];
    public int $resultCount = 0;
    public int $currentPage = 1;
    public int $perPage = 10;

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
        return in_array($user?->role, ['administrator', 'admin_toko', 'admin_gudang'], true);
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
                ];
            }
        } elseif ($this->reportType === 'jasa') {
            $jasa = Jasa::with(['pelanggan', 'petugasMany', 'items'])
                ->where('no_jasa', $this->singleNumber)
                ->first();
            
            if ($jasa) {
                $this->reportData = [
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
                ];
            }
        }
    }

    protected function loadPreviewData(): void
    {
        $query = $this->filters['report_type'] === 'produksi' 
            ? Produksi::with(['items'])
            : Jasa::with(['pelanggan', 'petugasMany', 'items']);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('createdAt', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('createdAt', '<=', $this->filters['end_date']);
        }

        $this->resultCount = $query->count();

        $offset = ($this->currentPage - 1) * $this->perPage;
        $items = $query->orderBy('createdAt', 'desc')
            ->skip($offset)
            ->take($this->perPage)
            ->get();

        $this->previewRows = $items->map(function ($item) {
            if ($this->filters['report_type'] === 'produksi') {
                return [
                    'number' => $item->no_produksi,
                    'no_ref' => $item->no_ref ?? '-',
                    'branch' => $item->branch ?? '-',
                    'items_summary' => $item->items->map(function ($i) {
                        return "{$i->nama_produksi} - {$i->nama_bahan} ({$i->jumlah})";
                    })->join("\n"),
                    'items_count' => $item->items->count(),
                    'total_harga' => $item->items->sum('harga'),
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
                    'petugas' => $item->petugasMany->isNotEmpty() 
                        ? $item->petugasMany->pluck('nama')->join(', ') 
                        : '-',
                    'scheduled_at' => $item->jadwal_petugas?->format('d/m/Y H:i') ?? '-',
                    'note' => $item->catatan,
                ];
            }
        })->toArray();
    }

    public function filterForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Tipe Laporan')
                    ->options([
                        'produksi' => 'Produksi',
                        'jasa' => 'Jasa',
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['report_type'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    }),
                
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['start_date'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    }),
                
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->filters['end_date'] = $state;
                        $this->currentPage = 1;
                        $this->loadPreviewData();
                    }),
            ])
            ->statePath('filters');
    }

    public function generatePdf(string $number, string $type, string $format = 'report'): void
    {
        if ($type === 'produksi') {
            $produksi = Produksi::with(['items'])->where('no_produksi', $number)->first();
            if (!$produksi) return;
            
            $data = [
                'row' => [
                    'number' => $produksi->no_produksi,
                    'no_ref' => $produksi->no_ref,
                    'branch' => $produksi->branch,
                    'status' => $produksi->status,
                    'catatan' => $produksi->catatan,
                    'created_at' => $produksi->createdAt?->format('d/m/Y H:i') ?? '-',
                    'items_count' => $produksi->items->count(),
                    'total_harga' => $produksi->items->sum('harga'),
                    'items' => $produksi->items->toArray(),
                ],
                'generatedAt' => now(),
            ];

            $view = $format === 'invoice' ? 'reports/pdf/produksi-invoice' : 'reports/pdf/produksi';
            $pdf = Pdf::loadView($view, $data);
            $filename = "{$type}-{$number}-{$format}.pdf";
        } else {
            $jasa = Jasa::with(['pelanggan', 'petugasMany', 'items'])
                ->where('no_jasa', $number)
                ->first();
            if (!$jasa) return;
            
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
                ],
                'generatedAt' => now(),
            ];

            $view = $format === 'invoice' ? 'reports/pdf/jasa-invoice' : 'reports/pdf/jasa';
            $pdf = Pdf::loadView($view, $data);
            $filename = "{$type}-{$number}-{$format}.pdf";
        }

        $pdf->stream($filename);
    }

    public function goToPage(int $page): void
    {
        $this->currentPage = $page;
        $this->loadPreviewData();
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
        $maxPage = ceil($this->resultCount / $this->perPage);
        if ($this->currentPage < $maxPage) {
            $this->currentPage++;
            $this->loadPreviewData();
        }
    }
}

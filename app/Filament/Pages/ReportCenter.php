<?php

namespace App\Filament\Pages;

use App\Models\Jasa;
use App\Models\Produksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ReportCenter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Report Produksi & Jasa';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $title = 'Report Center';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.report-center';

    protected const MAX_PREVIEW_ROWS = 25;

    /**
     * Active filter state.
     */
    public array $filters = [
        'report_type' => 'jasa',
        'start_date' => null,
        'end_date' => null,
    ];

    /**
     * Download mode: 'date_range' or 'single'
     */
    public string $downloadMode = 'date_range';

    /**
     * Show single download form
     */
    public bool $showSingleDownloadForm = false;

    /**
     * Single download form state
     */
    public array $singleDownloadFormData = [
        'singleDataNumber' => null,
    ];

    /**
     * Preview table rows rendered on the page.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $previewRows = [];

    /**
     * High-level summary metrics for the current filter.
     */
    public array $summary = [];

    /**
     * Status distribution used for quick analytics & PDF reports.
     *
     * @var array<int, array{status: string, count: int}>
     */
    public array $statusBreakdown = [];

    public int $resultCount = 0;

    protected array $statusMap = [
        'jasa' => [
            'jasa baru' => 'Jasa Baru',
            'terjadwal' => 'Terjadwal',
            'selesai dikerjakan' => 'Selesai Dikerjakan',
            'selesai' => 'Selesai',
        ],
        'produksi' => [
            'produksi baru' => 'Produksi Baru',
            'siap produksi' => 'Siap Produksi',
            'dalam pengerjaan' => 'Dalam Pengerjaan',
            'selesai dikerjakan' => 'Selesai Dikerjakan',
            'lolos qc' => 'Lolos QC',
            'produksi siap diambil' => 'Produksi Siap Diambil',
            'selesai' => 'Selesai',
        ],
    ];

    public function mount(): void
    {
        $this->filterForm->fill($this->filters);
        if (!isset($this->singleDownloadFormData)) {
            $this->singleDownloadFormData = ['singleDataNumber' => null];
        }
        $this->applyFilters();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $allowedRoles = [
            'administrator',
            'admin_toko',
            'admin_gudang',
            'kepala_teknisi_lapangan',
            'kepala_teknisi_gudang',
        ];

        return in_array(strtolower($user->role ?? ''), $allowedRoles, true);
    }

    /**
     * Expose the filter form to the page.
     */
    protected function getForms(): array
    {
        return [
            'filterForm',
            'singleDownloadForm',
        ];
    }

    public function filterForm($form)
    {
        return $form
            ->schema([
                Section::make('Pengaturan Filter')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->schema([
                        Select::make('report_type')
                            ->label('Jenis Report')
                            ->options([
                                'jasa' => 'Jasa & Layanan',
                                'produksi' => 'Produksi',
                            ])
                            ->default('jasa')
                            ->reactive()
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->maxDate(fn (callable $get) => $get('end_date'))
                            ->closeOnDateSelection(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->minDate(fn (callable $get) => $get('start_date'))
                            ->closeOnDateSelection(),
                    ]),
            ])
            ->statePath('filters');
    }

    public function singleDownloadForm($form)
    {
        return $form
            ->schema([
                TextInput::make('singleDataNumber')
                    ->label(fn () => ($this->filters['report_type'] ?? 'jasa') === 'produksi' ? 'No. Produksi' : 'No. Jasa')
                    ->placeholder(fn () => ($this->filters['report_type'] ?? 'jasa') === 'produksi' ? 'Masukkan No. Produksi' : 'Masukkan No. Jasa')
                    ->required()
                    ->maxLength(255)
                    ->reactive(),
            ])
            ->statePath('singleDownloadFormData');
    }

    public function toggleSingleDownloadForm(): void
    {
        $this->showSingleDownloadForm = !$this->showSingleDownloadForm;
        if (!$this->showSingleDownloadForm) {
            $this->singleDownloadFormData = ['singleDataNumber' => null];
            $this->singleDownloadForm->fill(['singleDataNumber' => null]);
        }
    }

    public function applyFilters(): void
    {
        $this->filters = array_merge($this->filters, $this->filterForm->getState());

        if (! $this->isDateRangeValid()) {
            return;
        }

        $records = $this->buildBaseQuery()
            ->whereRaw('LOWER(status) = ?', ['selesai']) // Only show completed items
            ->orderByDesc('createdAt')
            ->get();

        $this->resultCount = $records->count();
        $this->summary = $this->buildSummary($records);
        $this->statusBreakdown = $this->buildStatusBreakdown($records);
        $this->previewRows = $records
            ->take(self::MAX_PREVIEW_ROWS)
            ->map(fn ($record) => $this->transformRecord($record))
            ->values()
            ->toArray();
    }

    public function resetFilters(): void
    {
        $this->filters = $this->defaultFilters();
        $this->filterForm->fill($this->filters);
        $this->applyFilters();
    }

    public function downloadPdfByDateRange()
    {
        $this->filters = array_merge($this->filters, $this->filterForm->getState());
        $filters = $this->filters;

        if (! $this->isDateRangeValid()) {
            return null;
        }

        // Only get completed items for date range download
        $records = $this->buildBaseQuery()
            ->whereRaw('LOWER(status) = ?', ['selesai'])
            ->orderBy('createdAt')
            ->get();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data selesai yang cocok dengan filter rentang waktu saat ini.')
                ->warning()
                ->send();

            return null;
        }

        $rows = $records
            ->map(fn ($record) => $this->transformRecord($record))
            ->toArray();

        $view = $filters['report_type'] === 'produksi'
            ? 'reports.pdf.produksi'
            : 'reports.pdf.jasa';

        $payload = [
            'rows' => $rows,
            'filters' => $filters,
            'summary' => $this->buildSummary($records),
            'statusBreakdown' => $this->buildStatusBreakdown($records),
            'statusLabels' => $this->statusMap[$filters['report_type']] ?? [],
            'generatedAt' => Carbon::now(),
        ];

        $pdf = Pdf::loadView($view, $payload)
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $filename = sprintf(
            '%s-report-%s.pdf',
            $filters['report_type'],
            Carbon::now()->format('Ymd_His')
        );

        return response()->streamDownload(
            static fn () => print($pdf->output()),
            $filename
        );
    }

    public function downloadPdfSingle()
    {
        $this->filters = array_merge($this->filters, $this->filterForm->getState());
        
        // Get the form state
        $formState = $this->singleDownloadForm->getState();
        $singleDataNumber = $formState['singleDataNumber'] ?? null;
        
        $filters = $this->filters;
        $reportType = $filters['report_type'] ?? 'jasa';

        if (empty($singleDataNumber)) {
            Notification::make()
                ->title('Nomor tidak boleh kosong')
                ->body('Silakan masukkan nomor produksi atau nomor jasa.')
                ->danger()
                ->send();

            return null;
        }

        // Find the record by number
        if ($reportType === 'produksi') {
            $record = Produksi::query()
                ->with('team:id,nama')
                ->where('no_produksi', $singleDataNumber)
                ->first();
        } else {
            $record = Jasa::query()
                ->with([
                    'pelanggan:id,nama',
                    'petugasMany:id,nama',
                ])
                ->where('no_jasa', $singleDataNumber)
                ->first();
        }

        if (!$record) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body(sprintf('Tidak ada data dengan nomor %s.', $singleDataNumber))
                ->warning()
                ->send();

            return null;
        }

        // Validate status must be "selesai"
        if (strcasecmp($record->status ?? '', 'selesai') !== 0) {
            Notification::make()
                ->title('Data belum selesai')
                ->body(sprintf('Data dengan nomor %s belum selesai. Hanya data dengan status "Selesai" yang dapat diunduh.', $singleDataNumber))
                ->danger()
                ->send();

            return null;
        }

        $rows = [$this->transformRecord($record)];

        $view = $reportType === 'produksi'
            ? 'reports.pdf.produksi'
            : 'reports.pdf.jasa';

        $records = collect([$record]);

        $payload = [
            'rows' => $rows,
            'filters' => array_merge($filters, ['single_number' => $singleDataNumber]),
            'summary' => $this->buildSummary($records),
            'statusBreakdown' => $this->buildStatusBreakdown($records),
            'statusLabels' => $this->statusMap[$reportType] ?? [],
            'generatedAt' => Carbon::now(),
        ];

        $pdf = Pdf::loadView($view, $payload)
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $filename = sprintf(
            '%s-%s-%s.pdf',
            $reportType,
            $singleDataNumber,
            Carbon::now()->format('Ymd_His')
        );

        return response()->streamDownload(
            static fn () => print($pdf->output()),
            $filename
        );
    }

    protected function buildBaseQuery(): Builder
    {
        $reportType = $this->filters['report_type'] ?? 'jasa';

        $query = $reportType === 'produksi'
            ? Produksi::query()->with('team:id,nama')
            : Jasa::query()->with([
                'pelanggan:id,nama',
                'petugasMany:id,nama',
            ]);

        // Filter by date range
        if ($start = Arr::get($this->filters, 'start_date')) {
            $query->whereDate('createdAt', '>=', $start);
        }

        if ($end = Arr::get($this->filters, 'end_date')) {
            $query->whereDate('createdAt', '<=', $end);
        }

        // For date range downloads, only show completed items
        // This is handled in applyFilters and downloadPdf methods

        return $query;
    }

    protected function transformRecord($record): array
    {
        $timezone = config('app.timezone', 'UTC');
        $reportType = $this->filters['report_type'] ?? 'jasa';

        if ($reportType === 'produksi') {
            return [
                'number' => $record->no_produksi,
                'name' => $record->nama_produksi,
                'material' => $record->nama_bahan,
                'quantity' => $record->jumlah,
                'team' => $record->team->nama ?? '-',
                'status' => $record->status,
                'created_at' => $this->formatDate($record->createdAt, $timezone),
                'updated_at' => $this->formatDate($record->updateAt, $timezone),
                'note' => $record->catatan,
            ];
        }

        $petugasList = $record->relationLoaded('petugasMany')
            ? $record->petugasMany->pluck('nama')->filter()->implode(', ')
            : '';

        $scheduledAt = $record->jadwal ?? $record->jadwal_petugas;

        return [
            'number' => $record->no_jasa,
            'reference' => $record->no_ref,
            'service' => $record->jenis_layanan,
            'customer' => $record->pelanggan->nama ?? '-',
            'petugas' => $petugasList ?: '-',
            'status' => $record->status,
            'scheduled_at' => $this->formatDate($scheduledAt, $timezone, 'd/m/Y H:i'),
            'created_at' => $this->formatDate($record->createdAt, $timezone),
            'note' => $record->catatan,
        ];
    }

    protected function buildSummary(EloquentCollection $records): array
    {
        $completed = $records->filter(fn ($record) => strcasecmp($record->status ?? '', 'selesai') === 0)->count();

        return [
            'total' => $records->count(),
            'completed' => $completed,
            'in_progress' => max(0, $records->count() - $completed),
            'date_range' => $this->formatDateRange(),
        ];
    }

    protected function buildStatusBreakdown(EloquentCollection $records): array
    {
        $reportType = $this->filters['report_type'] ?? 'jasa';
        $labels = $this->statusMap[$reportType] ?? [];

        return $records
            ->groupBy(function ($item) {
                return strtolower($item->status ?? 'tanpa status');
            })
            ->map(function (Collection $items, string $status) use ($labels) {
                $label = $labels[$status] ?? ucwords($status);

                return [
                    'status' => $label,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->toArray();
    }

    protected function formatDate(?Carbon $value, string $timezone = 'UTC', string $format = 'd/m/Y H:i'): ?string
    {
        if (! $value) {
            return null;
        }

        return $value->copy()->timezone($timezone)->format($format);
    }

    protected function formatDateRange(): string
    {
        $start = Arr::get($this->filters, 'start_date');
        $end = Arr::get($this->filters, 'end_date');

        if (! $start && ! $end) {
            return 'Semua tanggal';
        }

        $formatter = static fn (?string $date) => $date
            ? Carbon::parse($date)->translatedFormat('d M Y')
            : null;

        $left = $formatter($start) ?? $formatter($end);
        $right = $formatter($end) ?? $formatter($start);

        if ($left && $right && $left === $right) {
            return $left;
        }

        return trim(sprintf('%s – %s', $left ?? '-', $right ?? '-'));
    }

    protected function getStatusOptions(?string $type = null): array
    {
        $type ??= $this->filters['report_type'] ?? 'jasa';

        return $this->statusMap[$type] ?? [];
    }

    protected function defaultFilters(): array
    {
        return [
            'report_type' => 'jasa',
            'start_date' => null,
            'end_date' => null,
        ];
    }

    protected function isDateRangeValid(): bool
    {
        $start = Arr::get($this->filters, 'start_date');
        $end = Arr::get($this->filters, 'end_date');

        if ($start && $end && $start > $end) {
            Notification::make()
                ->title('Rentang tanggal tidak valid')
                ->body('Tanggal mulai tidak boleh melebihi tanggal selesai.')
                ->danger()
                ->send();

            return false;
        }

        return true;
    }
}


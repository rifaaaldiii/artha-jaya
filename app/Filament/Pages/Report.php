<?php

namespace App\Filament\Pages;

use App\Models\Jasa;
use App\Models\Produksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Report extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Report';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $title = 'Report Center';

    protected static ?int $navigationSort = 5;

    public array $filters = [
        'report_type' => 'jasa',
        'start_date' => null,
        'end_date' => null,
    ];

    public string $reportType = 'jasa';

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

    public function mount(): void
    {
        $this->reportType = $this->filters['report_type'] ?? 'jasa';
    }

    protected function getForms(): array
    {
        return [
            'filterForm',
        ];
    }

    public function filterForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Jenis Report')
                    ->options([
                        'jasa' => 'Jasa & Layanan',
                        'produksi' => 'Produksi',
                    ])
                    ->default('jasa')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->reportType = $state;
                        $this->filters['report_type'] = $state;
                    })
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->maxDate(fn (callable $get) => $get('end_date'))
                    ->live(onBlur: false)
                    ->closeOnDateSelection(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->minDate(fn (callable $get) => $get('start_date'))
                    ->live(onBlur: false)
                    ->closeOnDateSelection(),
            ])
            ->statePath('filters')
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->filters([
                // Filters are handled by the form
            ])
            ->actions([
                Action::make('downloadPdf')
                    ->label('PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->action(fn ($record) => $this->downloadPdfSingle($record)),
                Action::make('downloadInvoice')
                    ->label('Invoice')
                    ->icon('heroicon-m-document-text')
                    ->color('primary')
                    ->action(fn ($record) => $this->downloadInvoiceSingle($record)),
            ])
            ->bulkActions([
                // No bulk actions needed
            ])
            ->headerActions([
                Action::make('downloadPdfRange')
                    ->label('Download PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => $this->downloadPdfByDateRange())
                    ->requiresConfirmation()
                    ->disabled(fn () => $this->getFilteredQuery()->count() === 0),
                Action::make('downloadInvoiceRange')
                    ->label('Download Invoice')
                    ->icon('heroicon-m-document-text')
                    ->color('primary')
                    ->action(fn () => $this->downloadInvoiceByDateRange())
                    ->requiresConfirmation()
                    ->disabled(fn () => $this->getFilteredQuery()->count() === 0),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50, 100]);
    }

    protected function getTableQuery(): Builder
    {
        return $this->getFilteredQuery();
    }

    protected function getFilteredQuery(): Builder
    {
        $reportType = $this->filters['report_type'] ?? 'jasa';

        $query = $reportType === 'produksi'
            ? Produksi::query()->with(['team:id,nama', 'items'])
            : Jasa::query()->with([
                'pelanggan:id,nama',
                'petugasMany:id,nama',
                'items',
            ]);

        // Only show completed items
        $query->whereRaw('LOWER(status) = ?', ['selesai']);

        // Filter by date range
        if ($start = Arr::get($this->filters, 'start_date')) {
            $query->whereDate('createdAt', '>=', $start);
        }

        if ($end = Arr::get($this->filters, 'end_date')) {
            $query->whereDate('createdAt', '<=', $end);
        }

        return $query->orderBy('id', 'asc');
    }

    protected function getTableColumns(): array
    {
        $reportType = $this->filters['report_type'] ?? 'jasa';

        if ($reportType === 'produksi') {
            return [
                TextColumn::make('no_produksi')
                    ->label('No Produksi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('no_ref')
                    ->label('No Ref')
                    ->placeholder('-'),
                BadgeColumn::make('branch')
                    ->label('Branch')
                    ->color('gray')
                    ->placeholder('-'),
                TextColumn::make('items_summary')
                    ->label('Detail Item')
                    ->getStateUsing(function ($record) {
                        if ($record->items && $record->items->count() > 0) {
                            return $record->items->map(function ($item, $index) {
                                return sprintf('%d. %s - %s (%s unit)',
                                    $index + 1,
                                    $item->nama_produksi,
                                    $item->nama_bahan,
                                    number_format($item->jumlah, 0, ',', '.')
                                );
                            })->implode("\n");
                        }
                        return '-';
                    })
                    ->limit(100)
                    ->tooltip(function ($state) {
                        return $state !== '-' ? $state : null;
                    }),
                TextColumn::make('items_count')
                    ->label('Jml')
                    ->getStateUsing(fn ($record) => $record->items ? $record->items->count() : 0)
                    ->alignCenter()
                    ->badge(),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->getStateUsing(function ($record) {
                        $total = $record->items ? $record->items->sum('harga') : 0;
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->alignRight()
                    ->weight('bold'),
                TextColumn::make('team.nama')
                    ->label('Team')
                    ->placeholder('-'),
                TextColumn::make('createdAt')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->color('gray'),
            ];
        }

        // Jasa columns
        return [
            TextColumn::make('no_jasa')
                ->label('No Jasa')
                ->searchable()
                ->sortable()
                ->weight('bold'),
            TextColumn::make('no_ref')
                ->label('No Ref')
                ->placeholder('-'),
            BadgeColumn::make('branch')
                ->label('Branch')
                ->color('gray')
                ->placeholder('-'),
            TextColumn::make('items_summary')
                ->label('Detail Item')
                ->getStateUsing(function ($record) {
                    if ($record->items && $record->items->count() > 0) {
                        return $record->items->map(function ($item, $index) {
                            return sprintf('%d. %s - Rp %s',
                                $index + 1,
                                $item->jenis_layanan,
                                number_format($item->harga ?? 0, 0, ',', '.')
                            );
                        })->implode("\n");
                    }
                    return '-';
                })
                ->limit(100)
                ->tooltip(function ($state) {
                    return $state !== '-' ? $state : null;
                }),
            TextColumn::make('items_count')
                ->label('Jml')
                ->getStateUsing(fn ($record) => $record->items ? $record->items->count() : 0)
                ->alignCenter()
                ->badge(),
            TextColumn::make('total_harga')
                ->label('Total Harga')
                ->getStateUsing(function ($record) {
                    $total = $record->items ? $record->items->sum('harga') : 0;
                    return 'Rp ' . number_format($total, 0, ',', '.');
                })
                ->alignRight()
                ->weight('bold'),
            TextColumn::make('pelanggan.nama')
                ->label('Pelanggan')
                ->placeholder('-'),
            TextColumn::make('petugas')
                ->label('Petugas')
                ->getStateUsing(function ($record) {
                    return $record->petugasMany->pluck('nama')->filter()->implode(', ') ?: '-';
                })
                ->limit(50),
            TextColumn::make('jadwal')
                ->label('Jadwal')
                ->dateTime('d/m/Y H:i')
                ->placeholder('-')
                ->color('gray'),
        ];
    }

    public function downloadPdfByDateRange()
    {
        $filters = $this->filters;

        $records = $this->getFilteredQuery()->get();

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
            ->setPaper('a5', 'landscape')
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

    public function downloadInvoiceByDateRange()
    {
        $filters = $this->filters;

        $records = $this->getFilteredQuery()->get();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data selesai yang cocok dengan filter rentang waktu saat ini.')
                ->warning()
                ->send();

            return null;
        }

        $rows = $records
            ->map(fn ($record) => $this->transformRecordForInvoice($record))
            ->toArray();

        $view = $filters['report_type'] === 'produksi'
            ? 'reports.pdf.produksi-invoice'
            : 'reports.pdf.jasa-invoice';

        $payload = [
            'rows' => $rows,
            'filters' => $filters,
            'summary' => $this->buildSummary($records),
            'statusBreakdown' => $this->buildStatusBreakdown($records),
            'statusLabels' => $this->statusMap[$filters['report_type']] ?? [],
            'generatedAt' => Carbon::now(),
        ];

        $pdf = Pdf::loadView($view, $payload)
            ->setPaper('a5', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $filename = sprintf(
            '%s-invoice-%s.pdf',
            $filters['report_type'],
            Carbon::now()->format('Ymd_His')
        );

        return response()->streamDownload(
            static fn () => print($pdf->output()),
            $filename
        );
    }

    public function downloadPdfSingle($record)
    {
        $rows = [$this->transformRecord($record)];

        $view = $this->reportType === 'produksi'
            ? 'reports.pdf.produksi'
            : 'reports.pdf.jasa';

        $records = collect([$record]);

        $payload = [
            'rows' => $rows,
            'filters' => array_merge($this->filters, ['single_number' => $this->reportType === 'produksi' ? $record->no_produksi : $record->no_jasa]),
            'summary' => $this->buildSummary($records),
            'statusBreakdown' => $this->buildStatusBreakdown($records),
            'statusLabels' => $this->statusMap[$this->reportType] ?? [],
            'generatedAt' => Carbon::now(),
        ];

        $pdf = Pdf::loadView($view, $payload)
            ->setPaper('a5', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $filename = sprintf(
            '%s-%s-%s.pdf',
            $this->reportType,
            $this->reportType === 'produksi' ? $record->no_produksi : $record->no_jasa,
            Carbon::now()->format('Ymd_His')
        );

        return response()->streamDownload(
            static fn () => print($pdf->output()),
            $filename
        );
    }

    public function downloadInvoiceSingle($record)
    {
        $rows = [$this->transformRecordForInvoice($record)];

        $view = $this->reportType === 'produksi'
            ? 'reports.pdf.produksi-invoice'
            : 'reports.pdf.jasa-invoice';

        $records = collect([$record]);

        $payload = [
            'row' => $rows[0],
            'filters' => array_merge($this->filters, ['single_number' => $this->reportType === 'produksi' ? $record->no_produksi : $record->no_jasa]),
            'summary' => $this->buildSummary($records),
            'statusBreakdown' => $this->buildStatusBreakdown($records),
            'statusLabels' => $this->statusMap[$this->reportType] ?? [],
            'generatedAt' => Carbon::now(),
        ];

        $pdf = Pdf::loadView($view, $payload)
            ->setPaper('a5', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        $filename = sprintf(
            '%s-invoice-%s-%s.pdf',
            $this->reportType,
            $this->reportType === 'produksi' ? $record->no_produksi : $record->no_jasa,
            Carbon::now()->format('Ymd_His')
        );

        return response()->streamDownload(
            static fn () => print($pdf->output()),
            $filename
        );
    }

    protected function transformRecord($record): array
    {
        $timezone = config('app.timezone', 'UTC');
        $reportType = $this->filters['report_type'] ?? 'jasa';

        if ($reportType === 'produksi') {
            $totalHarga = $record->items ? $record->items->sum('harga') : 0;
            $itemsCount = $record->items ? $record->items->count() : 0;
            
            $itemsSummary = '';
            if ($record->items && $record->items->count() > 0) {
                $itemsList = $record->items->map(function ($item, $index) {
                    return sprintf('%d. %s - %s (%s unit)', 
                        $index + 1,
                        $item->nama_produksi,
                        $item->nama_bahan,
                        number_format($item->jumlah, 0, ',', '.')
                    );
                })->implode("\n");
                $itemsSummary = $itemsList;
            }
            
            return [
                'id' => $record->id,
                'number' => $record->no_produksi,
                'no_ref' => $record->no_ref ?? '-',
                'branch' => $record->branch ?? '-',
                'items_count' => $itemsCount,
                'items_summary' => $itemsSummary,
                'total_harga' => $totalHarga,
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
        
        $totalHarga = $record->items ? $record->items->sum('harga') : 0;
        $itemsCount = $record->items ? $record->items->count() : 0;
        
        $itemsSummary = '';
        if ($record->items && $record->items->count() > 0) {
            $itemsList = $record->items->map(function ($item, $index) {
                return sprintf('%d. %s - Rp %s', 
                    $index + 1,
                    $item->jenis_layanan,
                    number_format($item->harga ?? 0, 0, ',', '.')
                );
            })->implode("\n");
            $itemsSummary = $itemsList;
        }

        return [
            'id' => $record->id,
            'number' => $record->no_jasa,
            'no_ref' => $record->no_ref ?? '-',
            'branch' => $record->branch ?? '-',
            'items_count' => $itemsCount,
            'items_summary' => $itemsSummary,
            'total_harga' => $totalHarga,
            'customer' => $record->pelanggan->nama ?? '-',
            'petugas' => $petugasList ?: '-',
            'status' => $record->status,
            'scheduled_at' => $this->formatDate($scheduledAt, $timezone, 'd/m/Y H:i'),
            'created_at' => $this->formatDate($record->createdAt, $timezone),
            'updated_at' => $this->formatDate($record->updateAt, $timezone),
            'note' => $record->catatan,
        ];
    }

    protected function transformRecordForInvoice($record): array
    {
        $timezone = config('app.timezone', 'UTC');
        $reportType = $this->filters['report_type'] ?? 'jasa';

        if ($reportType === 'produksi') {
            $totalHarga = $record->items ? $record->items->sum('harga') : 0;
            $itemsCount = $record->items ? $record->items->count() : 0;
            
            $items = [];
            if ($record->items && $record->items->count() > 0) {
                foreach ($record->items as $item) {
                    $items[] = [
                        'nama_produksi' => $item->nama_produksi ?? '-',
                        'nama_bahan' => $item->nama_bahan ?? '-',
                        'jumlah' => $item->jumlah ?? 0,
                        'harga' => $item->harga ?? 0,
                    ];
                }
            }
            
            return [
                'id' => $record->id,
                'number' => $record->no_produksi,
                'no_ref' => $record->no_ref ?? '-',
                'branch' => $record->branch ?? '-',
                'items_count' => $itemsCount,
                'items' => $items,
                'total_harga' => $totalHarga,
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
        
        $totalHarga = $record->items ? $record->items->sum('harga') : 0;
        $itemsCount = $record->items ? $record->items->count() : 0;
        
        $items = [];
        if ($record->items && $record->items->count() > 0) {
            foreach ($record->items as $item) {
                $items[] = [
                    'jenis_layanan' => $item->jenis_layanan ?? '-',
                    'harga' => $item->harga ?? 0,
                ];
            }
        }

        return [
            'id' => $record->id,
            'number' => $record->no_jasa,
            'no_ref' => $record->no_ref ?? '-',
            'branch' => $record->branch ?? '-',
            'items_count' => $itemsCount,
            'items' => $items,
            'total_harga' => $totalHarga,
            'customer' => $record->pelanggan->nama ?? '-',
            'petugas' => $petugasList ?: '-',
            'status' => $record->status,
            'scheduled_at' => $this->formatDate($scheduledAt, $timezone, 'd/m/Y H:i'),
            'created_at' => $this->formatDate($record->createdAt, $timezone),
            'updated_at' => $this->formatDate($record->updateAt, $timezone),
            'note' => $record->catatan,
        ];
    }

    protected function buildSummary($records): array
    {
        $completed = $records->filter(fn ($record) => strcasecmp($record->status ?? '', 'selesai') === 0)->count();

        return [
            'total' => $records->count(),
            'completed' => $completed,
            'in_progress' => max(0, $records->count() - $completed),
            'date_range' => $this->formatDateRange(),
        ];
    }

    protected function buildStatusBreakdown($records): array
    {
        $reportType = $this->filters['report_type'] ?? 'jasa';
        $labels = $this->statusMap[$reportType] ?? [];

        return $records
            ->groupBy(function ($item) {
                return strtolower($item->status ?? 'tanpa status');
            })
            ->map(function ($items, string $status) use ($labels) {
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
}

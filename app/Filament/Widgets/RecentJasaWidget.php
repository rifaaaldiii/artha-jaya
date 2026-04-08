<?php

namespace App\Filament\Widgets;

use App\Models\Jasa;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class RecentJasaWidget extends BaseWidget
{
    protected static ?string $heading = 'Jasa & Layanan Terbaru';
    
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected int $recordsPerPage = 5;

    public static function getDefaultTableRecordsPerPage(): int
    {
        return 5;
    }

    #[On('aj-refresh-jasa')]
    public function handleExternalRefresh(): void
    {
        $this->resetTable();
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Jasa::query()
                    ->with(['pelanggan', 'petugasMany', 'items'])
                    ->latest('createdAt')
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_jasa')
                    ->label('No. Jasa')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->pelanggan?->nama),
                \Filament\Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->getStateUsing(fn ($record) => $record->items->count() . ' item(s)')
                    ->badge()
                    ->color('info'),
                \Filament\Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->getStateUsing(fn ($record) => 'Rp ' . number_format($record->items->sum('harga') ?? 0, 0, ',', '.'))
                    ->weight('semibold')
                    ->color('success'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'terjadwal' => 'warning',
                        'jasa baru' => 'gray',
                        default => 'info',
                    }),
                \Filament\Tables\Columns\TextColumn::make('petugas_display')
                    ->label('Petugas')
                    ->getStateUsing(function ($record) {
                        if ($record->petugasMany && $record->petugasMany->count() > 0) {
                            return $record->petugasMany->pluck('nama')->join(', ');
                        }
                        return $record->petugas?->nama ?? '-';
                    })
                    ->limit(30),
            ])
            ->defaultSort('createdAt', 'desc')
            ->paginated(true);
    }
}

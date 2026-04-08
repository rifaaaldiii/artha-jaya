<?php

namespace App\Filament\Widgets;

use App\Models\Produksi;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class RecentProduksiWidget extends BaseWidget
{
    protected static ?string $heading = 'Produksi Terbaru';
    
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected int $recordsPerPage = 5;

    /**
     * Override the parent property to set records per page.
     */
    public static function getDefaultTableRecordsPerPage(): int
    {
        return 5;
    }

    #[On('aj-refresh-produksi')]
    public function handleExternalRefresh(): void
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produksi::query()
                    ->with(['team', 'items'])
                    ->latest('createdAt')
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_produksi')
                    ->label('No. Produksi')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->branch ? "Branch: {$record->branch}" : null),
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
                        'dalam pengerjaan' => 'warning',
                        'produksi baru' => 'gray',
                        default => 'info',
                    }),
                \Filament\Tables\Columns\TextColumn::make('team.nama')
                    ->label('Team')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),
            ])
            ->defaultSort('createdAt', 'desc')
            ->paginated(true);
    }
}

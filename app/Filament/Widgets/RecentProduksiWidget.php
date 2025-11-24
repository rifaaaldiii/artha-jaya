<?php

namespace App\Filament\Widgets;

use App\Models\produksi;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

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

    public function table(Table $table): Table
    {
        return $table
            ->query(
                produksi::query()
                    ->with('team')
                    ->latest('createdAt')
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_produksi')
                    ->label('No. Produksi')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                \Filament\Tables\Columns\TextColumn::make('nama_produksi_bahan')
                    ->label('Jenis')
                    ->getStateUsing(fn ($record) => "{$record->nama_produksi} - {$record->nama_bahan}")
                    ->searchable(['nama_produksi', 'nama_bahan'])
                    ->badge()
                    ->color('info'),
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
                    ->color('primary'),
            ])
            ->defaultSort('createdAt', 'desc')
            ->paginated(true);
    }
}

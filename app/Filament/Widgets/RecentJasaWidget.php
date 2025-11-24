<?php

namespace App\Filament\Widgets;

use App\Models\jasa;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

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
    public function table(Table $table): Table
    {
        return $table
            ->query(
                jasa::query()
                    ->with(['pelanggan', 'petugas'])
                    ->latest('createdAt')
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_jasa')
                    ->label('No. Jasa')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                \Filament\Tables\Columns\TextColumn::make('jenis_layanan')
                    ->label('Jenis')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'terjadwal' => 'warning',
                        'jasa baru' => 'gray',
                        default => 'info',
                    }),
                \Filament\Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->searchable(),
            ])
            ->defaultSort('createdAt', 'desc')
            ->paginated(true);
    }
}

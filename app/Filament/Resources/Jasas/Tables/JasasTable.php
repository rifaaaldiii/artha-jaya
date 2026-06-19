<?php

namespace App\Filament\Resources\Jasas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use App\Filament\Pages\ProgressJasa;
use App\Filament\Pages\Report;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Illuminate\Support\Carbon;

class JasasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => ProgressJasa::getUrl() . '?selectedJasaId=' . $record->id)
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_jasa')
                    ->label('No. Jasa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('no_ref')
                    ->label('No. Ref')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('branch')
                    ->label('Branch')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('kategori_names')
                    ->label('Kategori')
                    ->badge()
                    ->separator(',')
                    ->color('info')
                    ->placeholder('-')
                    ->getStateUsing(function ($record) {
                        return $record->items
                            ->filter(fn ($item) => $item->kategori)
                            ->pluck('kategori.nama')
                            ->unique()
                            ->values()
                            ->toArray();
                    })
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match (strtolower($state)) {
                        'jasa baru' => 'danger',
                        'batal' => 'danger',
                        'terjadwal' => 'info',
                        'selesai dikerjakan' => 'warning',
                        'selesai' => 'success',
                        default => 'secondary',
                    })
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('jadwal')
                    ->label('Jadwal')
                    ->getStateUsing(function ($record) {
                        // Use jadwal_petugas if exists, else use jadwal
                        return $record->jadwal_petugas ?? $record->jadwal;
                    })
                    ->date('d-m-Y')
                    ->sortable()
            ])
            ->filters([
                DateRangeFilter::make('createdAt')
                    ->label('Rentang Waktu')
                    ->format('d/m/Y')
                    ->timezone('Asia/Jakarta')
                    ->modifyQueryUsing(function (Builder $query, ?Carbon $startDate, ?Carbon $endDate, $dateString) {
                        return $query->when(!empty($dateString), function (Builder $query) use ($startDate, $endDate) {
                            $query->when($startDate, fn (Builder $query) => $query->whereDate('createdAt', '>=', $startDate))
                                  ->when($endDate, fn (Builder $query) => $query->whereDate('createdAt', '<=', $endDate));
                        });
                    }),
                SelectFilter::make('kategori_jasa_item_id')
                    ->label('Kategori Jasa')
                    ->relationship('items.kategori', 'nama'),
                Filter::make('ac_split_2pk')
                    ->label('JASA PASANG AC SPLIT WALL KAPASITAS 1,5-2PK')
                    ->query(fn (Builder $query): Builder => $query->whereHas('items', function (Builder $q) {
                        $q->where('kategori_jasa_item_id', 4)
                            ->whereIn('jenis_layanan', function ($subQuery) {
                                $subQuery->select('nama')
                                    ->from('jenis_jasas')
                                    ->where('kategori_id', 4)
                                    ->where('itemcode', '2');
                            });
                    })),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => ProgressJasa::getUrl() . '?selectedJasaId=' . $record->id),
                Action::make('invoice')
                    ->label('Print')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.pages.report') . '/preview-invoice?number=' . urlencode($record->no_jasa) . '&type=jasa', true)
                    ->openUrlInNewTab(),
                    // ->visible(fn ($record) => strtolower($record->status) === 'jasa baru'),
                Action::make('cancel')
                    ->label('Batal')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => strtolower($record->status) === 'jasa baru')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('cancelled_reason')
                            ->label('Alasan Pembatalan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'batal',
                            'cancelled_reason' => $data['cancelled_reason'],
                            'cancelled_at' => now(),
                            'cancelled_by' => Auth::id(),
                        ]);
                    })
                    ->successNotificationTitle('Jasa berhasil dibatalkan'),
            ])
            ->bulkActions([
                // Bulk delete removed - use cancel action instead
            ])
            ->defaultSort('createdAt', 'desc');
    }
}

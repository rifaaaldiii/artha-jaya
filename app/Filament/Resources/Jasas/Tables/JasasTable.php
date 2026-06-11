<?php

namespace App\Filament\Resources\Jasas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\ProgressJasa;
use App\Filament\Pages\Report;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Jasas\JasaResource;
use Filament\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

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
                DeleteAction::make()
                    ->authorize(fn ($record) => JasaResource::canDelete($record) && strtolower($record->status) === 'jasa baru'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(JasaResource::canDeleteAny())
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->filter(fn ($record) => strtolower($record->status) === 'jasa baru')
                                ->each(fn ($record) => $record->delete());
                        }),
                ]),
            ])
            ->defaultSort('createdAt', 'desc');
    }
}

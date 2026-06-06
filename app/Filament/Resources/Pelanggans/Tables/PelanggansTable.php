<?php

namespace App\Filament\Resources\Pelanggans\Tables;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PelanggansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_code')
                    ->label('Customer Code')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->kontak),

                TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->alamat),

                TextColumn::make('jasas_count')
                    ->label('Total Jasa')
                    ->counts('jasas')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('produksis_count')
                    ->label('Total Produksi')
                    ->counts('produksis')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('createdAt')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->filters([
            //     Filter::make('has_jasa')
            //         ->label('Sudah Ada Jasa')
            //         ->query(fn (Builder $query): Builder => $query->has('jasas')),

            //     Filter::make('no_jasa')
            //         ->label('Belum Ada Jasa')
            //         ->query(fn (Builder $query): Builder => $query->doesntHave('jasas')),
            // ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat Jasa')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.admin.resources.jasas.index', [
                        'tableFilters[pelanggan_id]' => $record->id,
                    ]))
                    ->visible(fn ($record) => $record->jasas()->count() > 0),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Customer')
                    ->modalDescription(function ($record) {
                        $hasJasa = $record->jasas()->exists();
                        $hasProduksi = $record->produksis()->exists();
                        
                        if ($hasJasa || $hasProduksi) {
                            $message = 'Customer ini tidak dapat dihapus karena memiliki data terkait: ';
                            $reasons = [];
                            if ($hasJasa) {
                                $reasons[] = $record->jasas()->count() . ' record Jasa';
                            }
                            if ($hasProduksi) {
                                $reasons[] = $record->produksis()->count() . ' record Produksi';
                            }
                            return $message . implode(', ', $reasons) . '. Hapus terlebih dahulu data Jasa/Produksi terkait.';
                        }
                        
                        return 'Apakah Anda yakin ingin menghapus customer ini?';
                    })
                    ->visible(fn ($record) => !$record->jasas()->exists() && !$record->produksis()->exists()),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Customer')
                    ->modalDescription('Apakah Anda yakin ingin menghapus customer yang dipilih? Customer dengan data Jasa/Produksi tidak akan dihapus.')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            // Only delete if customer has no Jasa or Produksi records
                            if (!$record->jasas()->exists() && !$record->produksis()->exists()) {
                                $record->delete();
                            }
                        });
                    }),
            ])
            ->defaultSort('createdAt', 'desc');
    }
}

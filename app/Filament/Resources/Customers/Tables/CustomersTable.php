<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Customer')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->kontak),

                TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(50)
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
                    ->color('primary'),

                TextColumn::make('createdAt')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            // ->filters([
            //     Filter::make('has_jasa')
            //         ->label('Sudah Ada Jasa')
            //         ->query(fn (Builder $query): Builder => $query->has('jasas')),

            //     Filter::make('no_jasa')
            //         ->label('Belum Ada Jasa')
            //         ->query(fn (Builder $query): Builder => $query->doesntHave('jasas')),

            //     Filter::make('has_produksi')
            //         ->label('Sudah Ada Produksi')
            //         ->query(fn (Builder $query): Builder => $query->has('produksis')),

            //     Filter::make('no_produksi')
            //         ->label('Belum Ada Produksi')
            //         ->query(fn (Builder $query): Builder => $query->doesntHave('produksis')),
            // ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat Jasa')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.admin.resources.jasas.index', [
                        'tableFilters[pelanggan_id]' => $record->id,
                    ]))
                    ->visible(fn ($record) => $record->jasas()->count() > 0),

                \Filament\Actions\Action::make('viewProduksi')
                    ->label('Lihat Produksi')
                    ->icon('heroicon-m-building-office')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.produksis.index', [
                        'tableFilters[pelanggan_id]' => $record->id,
                    ]))
                    ->visible(fn ($record) => $record->produksis()->count() > 0),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Customer')
                    ->modalDescription('Apakah Anda yakin ingin menghapus customer ini? Semua data terkait juga akan terpengaruh.'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Customer')
                    ->modalDescription('Apakah Anda yakin ingin menghapus customer yang dipilih?'),
            ])
            ->defaultSort('createdAt', 'desc');
    }
}

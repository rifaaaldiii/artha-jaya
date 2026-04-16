<?php

namespace App\Filament\Resources\Pelanggans\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PelanggansTable
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

                TextColumn::make('createdAt')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('has_jasa')
                    ->label('Sudah Ada Jasa')
                    ->query(fn (Builder $query): Builder => $query->has('jasas')),

                Filter::make('no_jasa')
                    ->label('Belum Ada Jasa')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('jasas')),
            ])
            ->actions([
                Action::make('view_jasas')
                    ->label('Lihat Jasa')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.jasas.index', [
                        'tableFilters[pelanggan_id]' => $record->id,
                    ]))
                    ->visible(fn ($record) => $record->jasas()->count() > 0),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalTitle('Hapus Customer')
                    ->modalDescription('Apakah Anda yakin ingin menghapus customer ini? Semua data terkait juga akan terpengaruh.')
                    ->modalSubmitActionLabel('Hapus'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalTitle('Hapus Customer')
                    ->modalDescription('Apakah Anda yakin ingin menghapus customer yang dipilih?'),
            ])
            ->defaultSort('createdAt', 'desc');
    }
}

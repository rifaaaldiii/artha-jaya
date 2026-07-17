<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('jadwal_petugas', 'desc')
            ->columns([
                TextColumn::make('jadwal_petugas')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40)
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('branch')
                    ->label('Branch')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make('alamat')
                    ->label('Lokasi')
                    ->limit(40)
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('pic')
                    ->label('PIC')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pekerja')
                    ->label('Pekerja')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Terjadwal' => 'info',
                        'Selesai' => 'success',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

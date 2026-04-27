<?php

namespace App\Filament\Resources\Teams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Produksi;

class TeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nama")
                    ->label('Nama Team')
                    ->sortable()
                    ->searchable(),

                TextColumn::make("order")
                    ->label('Order')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === 0 => 'success',
                        $state <= 2 => 'warning',
                        default => 'danger',
                    })
                    ->alignment('center')
                    ->description(fn ($state) => $state === 0 ? 'Non Active' : 'Aktif'),
                    
                TextColumn::make('capacity_status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->hasAvailableCapacity() ? 'Tersedia' : 'Penuh')
                    ->badge()
                    ->color(fn ($state) => $state === 'Tersedia' ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ])
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

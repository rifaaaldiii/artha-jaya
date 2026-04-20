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
                TextColumn::make("order")
                    ->label('Urutan')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->alignment('center'),
                TextColumn::make("nama")
                    ->label('Nama Team')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('active_produksis')
                    ->label('Produksi Aktif')
                    ->getStateUsing(fn ($record) => $record->getActiveProduksisCount())
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === 0 => 'success',
                        $state <= 2 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(query: function ($query, $direction) {
                        $query->orderBy(
                            Produksi::selectRaw('COUNT(*)')
                                ->whereColumn('team_id', 'teams.id')
                                ->where('status', '!=', 'selesai'),
                            $direction
                        );
                    }),
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

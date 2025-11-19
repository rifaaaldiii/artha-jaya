<?php

namespace App\Filament\Resources\Petukangs\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class PetukangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nama")->label('Nama')->sortable()->searchable(),
                TextColumn::make("kontak")->label('Kontak')->sortable()->searchable(),
                TextColumn::make('team.nama')
                    ->label('Team')
                    ->sortable()
                    ->searchable(),
                TextColumn::make("status")->label('Status')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

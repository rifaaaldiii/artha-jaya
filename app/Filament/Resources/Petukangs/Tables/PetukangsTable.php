<?php

namespace App\Filament\Resources\Petukangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                IconColumn::make("status")
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-badge')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->searchable()
                    ->extraAttributes(['class' => 'flex items-center gap-2'])
                    ->getStateUsing(fn ($record) => $record->status === 'ready'),
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

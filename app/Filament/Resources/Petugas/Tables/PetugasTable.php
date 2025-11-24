<?php

namespace App\Filament\Resources\Petugas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class PetugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nama")
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make("kontak")
                    ->label('Kontak')
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

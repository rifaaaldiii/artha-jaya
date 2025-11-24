<?php

namespace App\Filament\Resources\JenisProduksis\Tables;

use App\Filament\Resources\JenisProduksis\JenisProduksiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JenisProduksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->authorize(fn ($record) => JenisProduksiResource::canEdit($record)),
                DeleteAction::make()
                    ->authorize(fn ($record) => JenisProduksiResource::canDelete($record)),
            ]);
    }
}


<?php

namespace App\Filament\Resources\KategoriJasaItems\Tables;

use App\Filament\Resources\KategoriJasaItems\KategoriJasaItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategoriJasaItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No.')
                    ->rowIndex()
                    ->label('No.'),
                TextColumn::make('nama')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->wrap(),
            ])
            ->recordActions([
                EditAction::make()
                    ->authorize(fn ($record) => KategoriJasaItemResource::canEdit($record)),
                DeleteAction::make()
                    ->authorize(fn ($record) => KategoriJasaItemResource::canDelete($record)),
            ]);
    }
}

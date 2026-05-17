<?php

namespace App\Filament\Resources\Accessoris\Tables;

use App\Filament\Resources\Accessoris\AccessoriResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccessorisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No.')
                    ->rowIndex()
                    ->label('No.'),
                TextColumn::make('itemcode')
                    ->label('Item Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('uom')
                    ->label('UOM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisJasa.nama')
                    ->label('Jenis Jasa')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->authorize(fn ($record) => AccessoriResource::canEdit($record)),
                DeleteAction::make()
                    ->authorize(fn ($record) => AccessoriResource::canDelete($record)),
            ]);
    }
}

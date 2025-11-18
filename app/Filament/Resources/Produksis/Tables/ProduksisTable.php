<?php

namespace App\Filament\Resources\Produksis\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class ProduksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("createdAt")->label('Tanggal')->sortable()->searchable(),
                TextColumn::make("no_produksi")->label('No. Produksi')->sortable()->searchable(),
                TextColumn::make("jumlah")->label('Jumlah')->sortable()->searchable(),
                TextColumn::make("status")
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'produksi baru'           => 'danger',
                        'siap produksi'           => 'info',
                        'dalam pengerjaan'        => 'warning',
                        'selesai dikerjakan'      => 'success',
                        'lolos qc'                => 'primary',
                        'produksi siap diambil'   => 'secondary',
                        'selesai'                 => 'success',
                        default                   => 'secondary',
                    }),
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

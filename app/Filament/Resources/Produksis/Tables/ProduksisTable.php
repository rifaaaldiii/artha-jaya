<?php

namespace App\Filament\Resources\Produksis\Tables;

use App\Filament\Pages\Progress;
use App\Filament\Pages\Report;
use App\Filament\Resources\Produksis\ProduksiResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class ProduksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => Progress::getUrl() . '?selectedProduksiId=' . $record->id)
            ->columns([
                
                TextColumn::make("no_produksi")->label('No. Produksi')->sortable()->searchable(),
                TextColumn::make("no_ref")
                    ->label('No. Ref')
                    ->sortable()
                    ->searchable(),
                TextColumn::make("branch")
                    ->label('Branch')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->sortable(),
                TextColumn::make('team.nama')
                    ->label('Team')
                    ->sortable()
                    ->searchable(),
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
                TextColumn::make("createdAt")
                    ->label('Jadwal')
                    ->sortable()
                    ->searchable()
                    ->date('d-m-Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => Progress::getUrl() . '?selectedProduksiId=' . $record->id),
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => Report::getUrl() . '?report_type=produksi&single_number=' . $record->no_produksi, true)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => strtolower($record->status) === 'selesai'),
                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn ($record) => Report::getUrl() . '?report_type=produksi&single_number=' . $record->no_produksi . '&format=invoice', true)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => strtolower($record->status) === 'selesai'),
                EditAction::make()
                    ->authorize(fn ($record) => ProduksiResource::canEdit($record) && strtolower($record->status) !== 'selesai'),
                DeleteAction::make()
                    ->authorize(fn ($record) => ProduksiResource::canDelete($record) && strtolower($record->status) !== 'selesai'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(ProduksiResource::canDeleteAny())
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->filter(fn ($record) => strtolower($record->status) !== 'selesai')
                                ->each(fn ($record) => $record->delete());
                        }),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Jasas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\ProgressJasa;
use App\Filament\Pages\ReportCenter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Jasas\JasaResource;
use Filament\Actions\Action;

class JasasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => ProgressJasa::getUrl() . '?selectedJasaId=' . $record->id)
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_jasa')
                    ->label('No. Jasa')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('no_ref')
                    ->label('No. Ref')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('branch')
                    ->label('Branch')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                \Filament\Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match (strtolower($state)) {
                        'jasa baru' => 'danger',
                        'terjadwal' => 'info',
                        'selesai dikerjakan' => 'warning',
                        'selesai' => 'success',
                        default => 'secondary',
                    })
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('jadwal')
                    ->label('Jadwal')
                    ->getStateUsing(function ($record) {
                        // return jadwal if exists, else jadwal_petugas
                        return $record->jadwal ?? $record->jadwal_petugas;
                    })
                    ->date('d-m-Y')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => ProgressJasa::getUrl() . '?selectedJasaId=' . $record->id),
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => ReportCenter::getUrl() . '?report_type=jasa&single_number=' . $record->no_jasa, true)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => strtolower($record->status) === 'selesai'),
                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn ($record) => ReportCenter::getUrl() . '?report_type=jasa&single_number=' . $record->no_jasa . '&format=invoice', true)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => strtolower($record->status) === 'selesai'),
                EditAction::make()
                    ->authorize(fn ($record) => JasaResource::canEdit($record) && strtolower($record->status) !== 'selesai'),
                DeleteAction::make()
                    ->authorize(fn ($record) => JasaResource::canDelete($record) && strtolower($record->status) !== 'selesai'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(JasaResource::canDeleteAny())
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

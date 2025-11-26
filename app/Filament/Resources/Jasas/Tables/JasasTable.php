<?php

namespace App\Filament\Resources\Jasas\Tables;

use App\Filament\Pages\ProgressJasa;
use App\Filament\Resources\Jasas\JasaResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class JasasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => ProgressJasa::getUrl() . '?selectedJasaId=' . $record->id)
            ->poll('10s')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('no_jasa')
                    ->label('No. Jasa')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('jenis_layanan')
                    ->label('Nama Jasa')
                    ->sortable()
                    ->searchable(),
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
                    ->label('Jadwal Pelanggan')
                    ->date('d-m-Y')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => ProgressJasa::getUrl() . '?selectedJasaId=' . $record->id),
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

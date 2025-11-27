<?php

namespace App\Filament\Resources\Produksis\Tables;

use App\Filament\Pages\Progress;
use App\Filament\Resources\Produksis\ProduksiResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProduksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => Progress::getUrl() . '?selectedProduksiId=' . $record->id)
            ->columns([
                
                TextColumn::make("no_produksi")->label('No. Produksi')->sortable()->searchable(),
                TextColumn::make('nama_produksi_nama_bahan')
                    ->label('Nama Produksi')
                    ->sortable()
                    ->searchable(query: function ($query, string $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('nama_produksi', 'like', "%{$search}%")
                              ->orWhere('nama_bahan', 'like', "%{$search}%")
                              ->orWhereRaw("CONCAT(nama_produksi, '-' nama_bahan) like ?", ["%{$search}%"]);
                        });
                    })
                    ->getStateUsing(fn ($record) => $record->nama_produksi . '-' . $record->nama_bahan),
                TextColumn::make("jumlah")->label('Jumlah')->sortable()->searchable(),
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

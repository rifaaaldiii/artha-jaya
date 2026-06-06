<?php

namespace App\Filament\Resources\Pelanggans\Pages;

use App\Filament\Resources\Pelanggans\PelangganResource;
use App\Services\ErpCustomerSyncService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Throwable;

class ListPelanggans extends ManageRecords
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Customer')
                ->modalHeading('Customer Baru')
                ->modalWidth('lg'),

            Action::make('syncCustomers')
                ->label('Sync Customer')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Sync Customer dari SkyBiz')
                ->modalDescription('Sinkronkan data dari SkyBiz. Proses ini memerlukan beberapa detik bahkan menit.')
                ->modalSubmitActionLabel('Sync Sekarang')
                ->action(function (): void {
                    try {
                        $result = app(ErpCustomerSyncService::class)->sync();

                        Notification::make()
                            ->title('Sync customer selesai')
                            ->body(sprintf(
                                'Ditambahkan: %d, Diperbarui: %d, Dilewati: %d',
                                $result['created'],
                                $result['updated'],
                                $result['skipped'],
                            ))
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        Notification::make()
                            ->title('Sync customer gagal')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}

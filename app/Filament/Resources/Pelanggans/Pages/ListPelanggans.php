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
            Action::make('syncErpCustomers')
                ->label('Sync ERP')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Sync Pelanggan dari ERP')
                ->modalDescription('Tarik data terbaru dari Supabase (erp_customers) ke daftar pelanggan lokal. Proses ini bisa memakan waktu beberapa detik.')
                ->modalSubmitActionLabel('Sync Sekarang')
                ->action(function (ErpCustomerSyncService $syncService): void {
                    set_time_limit(300);

                    try {
                        $result = $syncService->sync();

                        Notification::make()
                            ->title('Sync berhasil')
                            ->body(sprintf(
                                'Total: %d | Baru: %d | Diperbarui: %d | Dilewati: %d',
                                $result['total'],
                                $result['created'],
                                $result['updated'],
                                $result['skipped'],
                            ))
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        Notification::make()
                            ->title('Sync gagal')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make()
                ->label('Tambah Customer')
                ->modalHeading('Customer Baru')
                ->modalWidth('lg'),
        ];
    }
}

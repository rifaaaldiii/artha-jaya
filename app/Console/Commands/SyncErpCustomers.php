<?php

namespace App\Console\Commands;

use App\Services\ErpCustomerSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncErpCustomers extends Command
{
    protected $signature = 'erp:sync-customers';

    protected $description = 'Sync customers from Supabase erp_customers into pelanggans table';

    public function handle(ErpCustomerSyncService $syncService): int
    {
        $this->info('Memulai sync pelanggan dari Supabase...');

        try {
            $result = $syncService->sync();
        } catch (Throwable $exception) {
            $this->error('Sync gagal: ' . $exception->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Metrik', 'Jumlah'],
            [
                ['Total ERP', $result['total']],
                ['Baru', $result['created']],
                ['Diperbarui', $result['updated']],
                ['Dilewati', $result['skipped']],
            ]
        );

        $this->info('Sync pelanggan selesai.');

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Services\AutoUpdateProduksiStatusService;
use Illuminate\Console\Command;

class AutoUpdateProduksiStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produksi:auto-update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-update produksi status dari baru ke proses jika tanggal jadwal sudah lewat';

    /**
     * Execute the console command.
     */
    public function handle(AutoUpdateProduksiStatusService $service): int
    {
        $this->info('Auto-updating produksi status (jadwal < '.now()->toDateString().')...');

        $updated = $service->run();
        $count = count($updated);

        if ($count === 0) {
            $this->info('No produksi records needed updating.');

            return self::SUCCESS;
        }

        $this->info("Updated {$count} produksi record(s) to status 'proses':");
        foreach ($updated as $noProduksi) {
            $this->line("  - {$noProduksi}");
        }

        return self::SUCCESS;
    }
}

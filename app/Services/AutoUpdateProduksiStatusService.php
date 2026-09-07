<?php

namespace App\Services;

use App\Models\Produksi;
use Illuminate\Support\Facades\Log;

class AutoUpdateProduksiStatusService
{
    /**
     * Update produksi with status "baru" to "proses" when jadwal date has passed.
     *
     * @return list<string> Updated no_produksi values
     */
    public function run(): array
    {
        $today = now()->toDateString();
        $updated = [];

        Produksi::query()
            ->where('status', 'baru')
            ->whereNotNull('jadwal')
            ->whereDate('jadwal', '<', $today)
            ->orderBy('id')
            ->chunkById(100, function ($produksis) use (&$updated): void {
                foreach ($produksis as $produksi) {
                    $produksi->update(['status' => 'proses']);
                    $updated[] = $produksi->no_produksi;
                }
            });

        Log::info('AutoUpdateProduksiStatusService completed', [
            'updated_count' => count($updated),
            'no_produksi' => $updated,
            'as_of_date' => $today,
        ]);

        return $updated;
    }
}

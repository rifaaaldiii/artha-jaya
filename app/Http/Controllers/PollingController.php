<?php

namespace App\Http\Controllers;

use App\Services\AutoUpdateProduksiStatusService;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PollingController extends Controller
{
    private const AUTO_UPDATE_LOCK = 'produksi:auto-update-status';

    private const AUTO_UPDATE_LAST_RUN = 'produksi:auto-update-status:last_run';

    private const AUTO_UPDATE_THROTTLE_SECONDS = 60;

    public function __invoke(Request $request, AutoUpdateProduksiStatusService $autoUpdateProduksiStatus)
    {
        $this->maybeAutoUpdateProduksiStatus($autoUpdateProduksiStatus);

        $channels = $request->query('channels');

        if (is_string($channels)) {
            $channels = array_filter(explode(',', $channels));
        } elseif (! is_array($channels)) {
            $channels = PollChannel::all();
        }

        $snapshot = PollTriggerStore::snapshot($channels);

        return response()->json([
            'interval' => (int) config('polling.interval_ms', 3000),
            'channels' => $snapshot,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    protected function maybeAutoUpdateProduksiStatus(AutoUpdateProduksiStatusService $service): void
    {
        if (Cache::has(self::AUTO_UPDATE_LAST_RUN)) {
            return;
        }

        $lock = Cache::lock(self::AUTO_UPDATE_LOCK, 10);

        if (! $lock->get()) {
            return;
        }

        try {
            if (Cache::has(self::AUTO_UPDATE_LAST_RUN)) {
                return;
            }

            $service->run();
            Cache::put(self::AUTO_UPDATE_LAST_RUN, true, self::AUTO_UPDATE_THROTTLE_SECONDS);
        } finally {
            $lock->release();
        }
    }
}

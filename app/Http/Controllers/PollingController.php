<?php

namespace App\Http\Controllers;

use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;
use Illuminate\Http\Request;

class PollingController extends Controller
{
    public function __invoke(Request $request)
    {
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
}


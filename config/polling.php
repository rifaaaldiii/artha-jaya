<?php

return [
    'interval_ms' => env('POLLING_INTERVAL_MS', 3000),

    'channels' => [
        'jasa',
        'produksi',
        'dashboard',
    ],

    'events' => [
        'jasa' => ['aj-refresh-jasa'],
        'produksi' => ['aj-refresh-produksi'],
        'dashboard' => ['aj-refresh-dashboard'],
    ],
];


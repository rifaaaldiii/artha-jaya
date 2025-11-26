<?php

namespace App\Support\Polling;

final class PollChannel
{
    public const JASA = 'jasa';
    public const PRODUKSI = 'produksi';
    public const DASHBOARD = 'dashboard';

    public static function all(): array
    {
        return config('polling.channels', [
            self::JASA,
            self::PRODUKSI,
            self::DASHBOARD,
        ]);
    }

    public static function isValid(string $channel): bool
    {
        return in_array($channel, self::all(), true);
    }
}


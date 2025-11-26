<?php

namespace App\Support\Polling;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class PollTriggerStore
{
    private const CACHE_PREFIX = 'poll_trigger:';

    public static function snapshot(array $channels): array
    {
        $channels = array_values(array_filter($channels, fn ($channel) => PollChannel::isValid($channel)));

        return collect($channels)
            ->mapWithKeys(function (string $channel) {
                return [$channel => self::getVersion($channel)];
            })
            ->toArray();
    }

    public static function bump(string | array $channels): void
    {
        foreach (Arr::wrap($channels) as $channel) {
            if (! PollChannel::isValid($channel)) {
                continue;
            }

            $key = self::cacheKey($channel);

            if (! Cache::has($key)) {
                Cache::forever($key, 1);
            }

            Cache::increment($key);
        }
    }

    public static function latestVersions(): array
    {
        return self::snapshot(PollChannel::all());
    }

    private static function getVersion(string $channel): int
    {
        $key = self::cacheKey($channel);

        if (! Cache::has($key)) {
            Cache::forever($key, 1);
        }

        return (int) Cache::get($key, 1);
    }

    private static function cacheKey(string $channel): string
    {
        return self::CACHE_PREFIX . $channel;
    }
}


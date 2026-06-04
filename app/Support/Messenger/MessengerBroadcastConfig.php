<?php

namespace App\Support\Messenger;

use App\Models\User;

class MessengerBroadcastConfig
{
    /**
     * @return array<string, mixed>
     */
    public static function resolve(?User $user = null): array
    {
        $userId = $user?->id;
        $connection = config('broadcasting.default');

        $base = [
            'enabled' => false,
            'driver' => null,
            'key' => null,
            'cluster' => null,
            'host' => null,
            'port' => 443,
            'scheme' => 'https',
            'authEndpoint' => url('/broadcasting/auth'),
            'userId' => $userId,
            'pollSeconds' => (int) config('messenger.poll_interval_seconds', 5),
            'heartbeatSeconds' => (int) config('messenger.presence_heartbeat_seconds', 60),
            'notificationsEnabled' => (bool) config('messenger.notifications_enabled', true),
            'notificationSound' => (bool) config('messenger.notification_sound', true),
            'messengerUrl' => url('/admin/messenger'),
        ];

        if ($connection === 'pusher') {
            $key = config('broadcasting.connections.pusher.key');

            return array_merge($base, [
                'enabled' => filled($key) && $userId,
                'driver' => 'pusher',
                'key' => $key,
                'cluster' => config('broadcasting.connections.pusher.options.cluster', 'mt1'),
            ]);
        }

        if ($connection === 'reverb') {
            $key = config('broadcasting.connections.reverb.key');

            return array_merge($base, [
                'enabled' => filled($key) && $userId,
                'driver' => 'reverb',
                'key' => $key,
                'host' => config('broadcasting.connections.reverb.options.host'),
                'port' => (int) config('broadcasting.connections.reverb.options.port', 443),
                'scheme' => config('broadcasting.connections.reverb.options.scheme', 'https'),
            ]);
        }

        return $base;
    }
}

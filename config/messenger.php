<?php

return [
    'message_max_length' => 2000,
    'messages_per_page' => 50,
    'presence_ttl_seconds' => 120,
    'presence_heartbeat_seconds' => 60,
    'poll_interval_seconds' => 5,

  /*
  |--------------------------------------------------------------------------
  | Realtime notifications (Pusher cloud — cocok shared hosting)
  |--------------------------------------------------------------------------
  */
    'notifications_enabled' => env('MESSENGER_NOTIFICATIONS_ENABLED', true),
    'notification_sound' => env('MESSENGER_NOTIFICATION_SOUND', true),
];

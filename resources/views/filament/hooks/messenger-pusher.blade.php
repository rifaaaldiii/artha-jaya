@php
    use App\Support\Messenger\MessengerBroadcastConfig;

    $messengerBroadcast = auth()->check()
        ? MessengerBroadcastConfig::resolve(auth()->user())
        : ['enabled' => false];
@endphp

@if ($messengerBroadcast['enabled'] && ($messengerBroadcast['notificationsEnabled'] ?? true))
    <script>
        window.__messengerPusherConfig = @json($messengerBroadcast);
    </script>
    @vite(['resources/js/messenger-notifications.js'])

    <style>
        #messenger-toast-root {
            position: fixed;
            right: 16px;
            bottom: 16px;
            z-index: 99999;
            max-width: min(360px, calc(100vw - 32px));
        }

        .messenger-toast {
            position: relative;
            padding: 14px 40px 14px 16px;
            border-radius: 12px;
            background: #111b21;
            color: #e9edef;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.28);
            border: 1px solid #2a3942;
        }

        .messenger-toast strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .messenger-toast p {
            margin: 0 0 10px;
            font-size: 13px;
            color: #aebac1;
            line-height: 1.4;
        }

        .messenger-toast-open {
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            background: #00a884;
            color: #fff;
        }

        .messenger-toast-close {
            position: absolute;
            top: 8px;
            right: 10px;
            border: none;
            background: transparent;
            color: #aebac1;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
        }
    </style>
@endif

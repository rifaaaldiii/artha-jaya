/**
 * Global Messenger notifications via Pusher (shared hosting friendly).
 * Uses pusher-js directly — no Reverb/VPS required.
 */
import Pusher from 'pusher-js';

const config = window.__messengerPusherConfig;

if (config?.enabled && config?.notificationsEnabled !== false) {
    initMessengerNotifications(config);
}

function initMessengerNotifications(cfg) {
    const pusherOptions = {
        cluster: cfg.cluster ?? 'mt1',
        forceTLS: true,
        authEndpoint: cfg.authEndpoint,
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
        },
    };

    const pusher = new Pusher(cfg.key, pusherOptions);
    const channelName = `private-App.Models.User.${cfg.userId}`;
    const channel = pusher.subscribe(channelName);

    channel.bind('messenger.incoming', (payload) => {
        handleIncomingMessage(cfg, payload);
    });

    requestNotificationPermission();
}

function handleIncomingMessage(cfg, payload) {
    const message = payload?.message;

    if (!message || Number(message.sender_id) === Number(cfg.userId)) {
        return;
    }

    const activeConversationId = window.__messengerActiveConversationId;
    const onMessengerPage = window.location.pathname.includes('/admin/messenger');

    if (onMessengerPage && Number(activeConversationId) === Number(message.conversation_id)) {
        if (window.Livewire?.dispatch) {
            window.Livewire.dispatch('messenger-message-received', { message });
        }

        return;
    }

    if (cfg.notificationSound !== false) {
        playNotificationSound();
    }

    showBrowserNotification(cfg, payload);
    refreshNavigationBadge();
}

function refreshNavigationBadge() {
    if (window.Livewire?.dispatch) {
        window.Livewire.dispatch('refresh-navigation-badge');
    }
}

function requestNotificationPermission() {
    if (!('Notification' in window)) {
        return;
    }

    if (Notification.permission === 'default') {
        Notification.requestPermission().catch(() => {});
    }
}

function showBrowserNotification(cfg, payload) {
    const senderName = payload.sender_name ?? 'Pengguna';
    const preview = payload.preview ?? '';
    const title = `Pesan baru dari ${senderName}`;
    const body = preview || 'Anda menerima pesan Messenger baru.';
    const url = cfg.messengerUrl ?? '/admin/messenger';

    if ('Notification' in window && Notification.permission === 'granted') {
        const notification = new Notification(title, {
            body,
            icon: '/favicon.png',
            tag: `messenger-${payload.conversation_id}-${payload.message?.id ?? Date.now()}`,
        });

        notification.onclick = () => {
            window.focus();
            window.location.href = url;
            notification.close();
        };

        return;
    }

    showInPageToast(title, body, url);
}

function showInPageToast(title, body, url) {
    const existing = document.getElementById('messenger-toast-root');

    if (existing) {
        existing.remove();
    }

    const root = document.createElement('div');
    root.id = 'messenger-toast-root';
    root.innerHTML = `
        <div class="messenger-toast" role="alert">
            <strong>${escapeHtml(title)}</strong>
            <p>${escapeHtml(body)}</p>
            <button type="button" class="messenger-toast-open">Buka Messenger</button>
            <button type="button" class="messenger-toast-close" aria-label="Tutup">&times;</button>
        </div>
    `;

    document.body.appendChild(root);

    const toast = root.querySelector('.messenger-toast');
    root.querySelector('.messenger-toast-close')?.addEventListener('click', () => root.remove());
    root.querySelector('.messenger-toast-open')?.addEventListener('click', () => {
        window.location.href = url;
    });

    setTimeout(() => root.remove(), 8000);

    toast?.animate(
        [{ transform: 'translateX(120%)' }, { transform: 'translateX(0)' }],
        { duration: 280, easing: 'ease-out' },
    );
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;

    return div.innerHTML;
}

function playNotificationSound() {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;

        if (!AudioContext) {
            return;
        }

        const ctx = new AudioContext();
        const playTone = (frequency, startTime, duration) => {
            const oscillator = ctx.createOscillator();
            const gain = ctx.createGain();
            oscillator.type = 'sine';
            oscillator.frequency.value = frequency;
            gain.gain.setValueAtTime(0.0001, startTime);
            gain.gain.exponentialRampToValueAtTime(0.2, startTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);
            oscillator.connect(gain);
            gain.connect(ctx.destination);
            oscillator.start(startTime);
            oscillator.stop(startTime + duration);
        };

        const now = ctx.currentTime;
        playTone(880, now, 0.12);
        playTone(1174, now + 0.14, 0.18);

        setTimeout(() => ctx.close().catch(() => {}), 500);
    } catch {
        // Browser may block audio without user gesture.
    }
}

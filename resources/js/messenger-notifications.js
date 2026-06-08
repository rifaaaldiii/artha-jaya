/**
 * Global Messenger notifications via Pusher (shared hosting friendly).
 * Uses pusher-js directly — no Reverb/VPS required.
 */
import Pusher from 'pusher-js';

const config = window.__messengerPusherConfig;

// Expose updateSidebarBadge globally so Livewire components can trigger badge updates
window.__updateMessengerSidebarBadge = updateSidebarBadge;

// Listen for browser events dispatched by Livewire (e.g. when messages are read)
window.addEventListener('messenger-badge-updated', (event) => {
    updateSidebarBadge(event.detail?.total_unread);
});

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
    refreshNavigationBadge(payload?.total_unread);
}

function refreshNavigationBadge(totalUnread) {
    // 1. Update the Filament sidebar badge directly in the DOM (works on any page)
    updateSidebarBadge(totalUnread);

    // 2. Also dispatch Livewire event for pages that have the Messenger component
    if (window.Livewire?.dispatch) {
        window.Livewire.dispatch('refresh-navigation-badge');
    }
}

/**
 * Directly update the Messenger navigation badge in the Filament sidebar.
 * This works regardless of which page the user is on.
 */
function updateSidebarBadge(totalUnread) {
    // Find the sidebar item whose link points to the messenger page
    const messengerLink = document.querySelector(
        '.fi-sidebar-item a[href*="/admin/messenger"]'
    );

    if (!messengerLink) {
        return;
    }

    const sidebarItem = messengerLink.closest('.fi-sidebar-item');
    if (!sidebarItem) {
        return;
    }

    let badgeCtn = sidebarItem.querySelector('.fi-sidebar-item-badge-ctn');
    let badge = badgeCtn?.querySelector('.fi-badge');
    let badgeLabel = badge?.querySelector('.fi-badge-label');

    const count = Number(totalUnread) || 0;

    if (count <= 0) {
        // Remove badge if count is 0
        if (badgeCtn) {
            badgeCtn.remove();
        }
        return;
    }

    const countStr = count > 99 ? '99+' : String(count);

    if (badgeCtn && badge && badgeLabel) {
        // Update existing badge
        badgeLabel.textContent = countStr;
    } else {
        // Create badge elements matching Filament's structure
        badgeCtn = document.createElement('span');
        badgeCtn.className = 'fi-sidebar-item-badge-ctn';

        badge = document.createElement('span');
        badge.className = 'fi-badge fi-color-success';

        const labelCtn = document.createElement('span');
        labelCtn.className = 'fi-badge-label-ctn';

        badgeLabel = document.createElement('span');
        badgeLabel.className = 'fi-badge-label';
        badgeLabel.textContent = countStr;

        labelCtn.appendChild(badgeLabel);
        badge.appendChild(labelCtn);
        badgeCtn.appendChild(badge);
        messengerLink.appendChild(badgeCtn);
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

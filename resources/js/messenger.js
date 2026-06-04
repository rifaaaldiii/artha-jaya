import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.messengerPage = function messengerPage(config, conversationId) {
    return {
        config,
        echo: null,
        conversationId: conversationId,
        presenceLabel: '',
        showChat: Boolean(conversationId),
        isMobile: false,
        mobileMq: null,

        init() {
            this.mobileMq = window.matchMedia('(max-width: 768px)');
            this.isMobile = this.mobileMq.matches;
            this.mobileMq.addEventListener('change', (e) => {
                this.isMobile = e.matches;
                if (!this.isMobile) {
                    this.showChat = true;
                }
            });

            this.syncPresenceFromServer();

            if (config.enabled) {
                this.initEcho(config);
            }

            Livewire.hook('message.processed', () => {
                this.syncPresenceFromServer();

                const newId = this.$wire?.conversationId;
                if (newId && newId !== this.conversationId && config.enabled) {
                    this.subscribeConversation(newId);
                }
                if (newId && this.isMobile) {
                    this.showChat = true;
                }
                if (newId) {
                    this.$nextTick(() => scrollMessengerToBottom());
                }
            });

            setInterval(() => {
                this.$wire?.heartbeat();
            }, (config.heartbeatSeconds ?? 60) * 1000);

            Livewire.on('messenger-scroll-bottom', () => scrollMessengerToBottom());
        },

        syncPresenceFromServer() {
            const selectedId = this.$wire?.selectedUserId;

            if (!selectedId) {
                this.presenceLabel = '';

                return;
            }

            const online = Boolean(this.$wire?.selectedUserOnline);
            this.presenceLabel = online ? 'Online' : 'Offline';
        },

        openChat() {
            if (this.isMobile) {
                this.showChat = true;
            }

            this.$nextTick(() => scrollMessengerToBottom());
        },

        scrollToBottom() {
            scrollMessengerToBottom();
        },

        backToList() {
            this.showChat = false;
        },

        initEcho(config) {
            const scheme = config.scheme === 'https';
            const port = config.port || (scheme ? 443 : 80);

            this.echo = new Echo({
                broadcaster: 'reverb',
                key: config.key,
                wsHost: config.host,
                wsPort: port,
                wssPort: port,
                forceTLS: scheme,
                enabledTransports: ['ws', 'wss'],
                authEndpoint: config.authEndpoint,
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                },
            });

            this.echo.join('messenger.presence')
                .here((users) => this.updatePresenceLabel(users))
                .joining((user) => {
                    this.$wire?.heartbeat();
                    this.updatePresenceLabelFromEcho(user);
                })
                .leaving((user) => {
                    this.$wire?.heartbeat();
                    this.updatePresenceLabelFromEchoLeaving(user);
                })
                .error(() => {
                    this.syncPresenceFromServer();
                });

            this.subscribeConversation(this.conversationId);
        },

        subscribeConversation(id) {
            if (!this.echo || !id) {
                return;
            }

            if (this.channel) {
                this.echo.leave(`conversation.${this.conversationId}`);
            }

            this.conversationId = id;
            this.channel = this.echo.private(`conversation.${id}`);

            this.channel.listen('.message.sent', (payload) => {
                Livewire.dispatch('messenger-message-received', payload.message);
            });

            this.channel.listen('.message.delivered', (payload) => {
                Livewire.dispatch('messenger-message-delivered', payload);
            });

            this.channel.listen('.message.read', (payload) => {
                Livewire.dispatch('messenger-message-read', payload);
            });
        },

        updatePresenceLabel(users) {
            const selectedId = this.$wire?.selectedUserId;
            if (!selectedId) {
                this.presenceLabel = '';

                return;
            }

            const online = users.some((u) => Number(u.id) === Number(selectedId));
            this.presenceLabel = online ? 'Online' : 'Offline';
        },

        updatePresenceLabelFromEcho(user) {
            const selectedId = this.$wire?.selectedUserId;
            if (selectedId && Number(user?.id) === Number(selectedId)) {
                this.presenceLabel = 'Online';
            }
        },

        updatePresenceLabelFromEchoLeaving(user) {
            const selectedId = this.$wire?.selectedUserId;
            if (selectedId && Number(user?.id) === Number(selectedId)) {
                this.presenceLabel = 'Offline';
            }
        },
    };
};

const EMOJI_CATEGORIES = [
    {
        id: 'smileys',
        icon: '😊',
        emojis: [
            '😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🥰', '😍', '🤩',
            '😘', '😗', '😚', '😙', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐',
        ],
    },
    {
        id: 'gestures',
        icon: '👍',
        emojis: [
            '👍', '👎', '👊', '✊', '🤛', '🤜', '🤞', '✌️', '🤟', '🤘', '👌', '🤏', '👈', '👉', '👆',
            '👇', '☝️', '✋', '🤚', '🖐️', '🖖', '👋', '🤙', '💪', '🙏', '✍️', '💅', '🤳', '💃', '🕺', '👯',
        ],
    },
    {
        id: 'hearts',
        icon: '❤️',
        emojis: [
            '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🤍', '🤎', '💔', '❤️‍🔥', '❤️‍🩹', '💕', '💞', '💓', '💗',
            '💖', '💘', '💝', '💟', '♥️', '💋', '💌', '💐', '🌹', '🥀', '🌺', '🌸', '💮', '🏵️', '🌻', '🌼',
        ],
    },
    {
        id: 'objects',
        icon: '🎉',
        emojis: [
            '🎉', '🎊', '🎈', '🎁', '🏆', '🥇', '🥈', '🥉', '⚽', '🏀', '🎮', '🎯', '🎵', '🎶', '📱', '💻',
            '⌚', '📷', '🔔', '💡', '🔥', '⭐', '🌟', '✨', '💯', '✅', '❌', '❓', '❗', '💬', '👀', '🙈',
        ],
    },
    {
        id: 'food',
        icon: '🍕',
        emojis: [
            '🍕', '🍔', '🍟', '🌭', '🍿', '🧁', '🍰', '🎂', '🍩', '🍪', '🍫', '🍬', '🍭', '🍦', '🧋', '☕',
            '🍵', '🥤', '🍺', '🍻', '🥂', '🍷', '🍾', '🥗', '🍜', '🍣', '🍱', '🥟', '🍳', '🥐', '🍞', '🧀',
        ],
    },
    {
        id: 'nature',
        icon: '🌿',
        emojis: [
            '🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔',
            '🌞', '🌙', '⭐', '🌈', '☁️', '⛈️', '❄️', '🌊', '🌴', '🌵', '🌿', '🍀', '🌻', '🌸', '🍁', '🍂',
        ],
    },
];

window.messengerComposer = function messengerComposer() {
    return {
        draft: '',
        emojiOpen: false,
        activeEmojiCategory: 0,
        emojiCategories: EMOJI_CATEGORIES,
        sending: false,

        get activeEmojis() {
            return this.emojiCategories[this.activeEmojiCategory]?.emojis ?? [];
        },

        toggleEmojiPicker() {
            this.emojiOpen = !this.emojiOpen;
        },

        closeEmojiPicker() {
            this.emojiOpen = false;
        },

        insertEmoji(emoji) {
            const el = this.$refs.messageInput;
            const start = el?.selectionStart ?? this.draft.length;
            const end = el?.selectionEnd ?? this.draft.length;
            // Trailing space keeps UTF-16 cursor aligned (same as typing space manually).
            const insertion = `${emoji} `;
            const next = this.draft.slice(0, start) + insertion + this.draft.slice(end);

            if (next.length > 2000) {
                return;
            }

            this.draft = next;

            this.$nextTick(() => {
                if (!el) {
                    return;
                }

                el.focus();
                const pos = start + insertion.length;

                try {
                    el.setSelectionRange(pos, pos);
                } catch {
                    // Ignore if the textarea is not focusable yet.
                }
            });
        },

        async sendMessage() {
            const body = this.draft.trim();

            if (!body || this.sending) {
                return;
            }

            this.sending = true;

            try {
                await this.$wire.sendMessage(body);
                this.draft = '';
                this.closeEmojiPicker();
            } finally {
                this.sending = false;
            }
        },
    };
};

function scrollMessengerToBottom() {
    const el = document.getElementById('messenger-messages');
    if (!el) {
        return;
    }

    const scroll = () => {
        el.scrollTop = el.scrollHeight;
    };

    scroll();
    requestAnimationFrame(() => {
        scroll();
        requestAnimationFrame(scroll);
    });
}

window.scrollMessengerToBottom = scrollMessengerToBottom;

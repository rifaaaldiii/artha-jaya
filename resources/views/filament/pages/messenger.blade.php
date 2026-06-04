@php
    $broadcast = $this->getBroadcastConfig();
    $selectedUser = $this->getSelectedUser();
@endphp

<x-filament-panels::page>
    <div
        class="messenger-root"
        @if (! $broadcast['enabled'])
            wire:poll.{{ $broadcast['pollSeconds'] }}s="pollRefresh"
        @endif
        wire:init="heartbeat"
        x-data="messengerPage(@js($broadcast), @js($this->conversationId))"
        x-init="init()"
        x-bind:class="{ 'messenger-chat-open': showChat }"
    >
        <div class="messenger-layout">
            <aside
                class="messenger-sidebar"
                x-show="!isMobile || !showChat"
                x-cloak
            >
                {{-- <div class="messenger-sidebar-header">
                    <h1 class="messenger-sidebar-title">Artha Messenger</h1>
                </div> --}}

                <div class="messenger-search">
                    <span class="messenger-search-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5-1.5 1.5-5-5v-.79l-.27-.27A6.516 6.516 0 0 1 9.5 16 6.5 6.5 0 1 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14 14 12 14 9.5 12 5 9.5 5z"/>
                        </svg>
                    </span>
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="userSearch"
                        placeholder="Cari atau mulai chat baru"
                        class="messenger-input messenger-search-input"
                    />
                </div>

                <ul class="messenger-list">
                    @forelse ($chatList as $item)
                        @php
                            $isActive = $item['kind'] === 'conversation'
                                ? $conversationId === $item['conversation_id']
                                : $selectedUserId === $item['user_id'] && ! $conversationId;
                        @endphp
                        <li
                            wire:key="chat-{{ $item['kind'] }}-{{ $item['user_id'] }}-{{ $item['conversation_id'] }}"
                            @if ($item['kind'] === 'conversation')
                                wire:click="selectConversation({{ $item['conversation_id'] }})"
                            @else
                                wire:click="selectUser({{ $item['user_id'] }})"
                            @endif
                            @click="openChat()"
                            class="messenger-list-item {{ $isActive ? 'is-active' : '' }}"
                        >
                            <div class="messenger-avatar-wrap">
                                @if ($item['image_url'])
                                    <img src="{{ $item['image_url'] }}" alt="" class="messenger-avatar" loading="lazy" />
                                @else
                                    <div class="messenger-avatar messenger-avatar-placeholder">
                                        {{ strtoupper(substr($item['name'], 0, 1)) }}
                                    </div>
                                @endif
                                <span class="messenger-presence {{ $item['online'] ? 'is-online' : '' }}"></span>
                            </div>
                            <div class="messenger-list-body">
                                <div class="messenger-list-top">
                                    <span class="messenger-name">{{ $item['name'] }}</span>
                                    @if ($item['last_message_at'])
                                        <span class="messenger-time">{{ $item['last_message_at'] }}</span>
                                    @endif
                                </div>
                                <div class="messenger-list-bottom">
                                    @if ($item['kind'] === 'conversation')
                                        <span class="messenger-preview">{{ Str::limit($item['last_message'] ?? 'Belum ada pesan', 42) }}</span>
                                        @if ($item['unread_count'] > 0)
                                            <span class="messenger-badge">{{ $item['unread_count'] }}</span>
                                        @endif
                                    @else
                                        <span class="messenger-preview messenger-preview-muted">Ketuk untuk memulai chat</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="messenger-empty">Tidak ada pengguna ditemukan.</li>
                    @endforelse
                </ul>
            </aside>

            <section
                class="messenger-chat"
                x-show="!isMobile || showChat"
                x-cloak
            >
                @if ($conversationId && $selectedUser)
                    <header class="messenger-chat-header">
                        <button
                            type="button"
                            class="messenger-back-btn"
                            x-show="isMobile"
                            @click="backToList()"
                            aria-label="Kembali ke daftar chat"
                        >
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                            </svg>
                        </button>
                        <div class="messenger-avatar-wrap">
                            @if ($selectedUser->messengerAvatarUrl())
                                <img src="{{ $selectedUser->messengerAvatarUrl() }}" alt="" class="messenger-avatar" />
                            @else
                                <div class="messenger-avatar messenger-avatar-placeholder">
                                    {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="messenger-chat-header-info">
                            <h2 class="messenger-chat-title">{{ $selectedUser->name }}</h2>
                            <p
                                class="messenger-chat-subtitle"
                                x-show="presenceLabel"
                                x-text="presenceLabel"
                                :class="presenceLabel === 'Online' ? 'is-online' : 'is-offline'"
                            ></p>
                        </div>
                    </header>

                    <div
                        class="messenger-messages"
                        id="messenger-messages"
                        wire:ignore.self
                        x-init="$nextTick(() => scrollMessengerToBottom())"
                    >
                        @if ($hasMoreMessages)
                            <button type="button" wire:click="loadOlderMessages" class="messenger-load-more">
                                Muat pesan sebelumnya
                            </button>
                        @endif

                        @foreach ($messages as $msg)
                            @php
                                $isMine = (int) $msg['sender_id'] === auth()->id();
                                $status = $msg['read_status'] ?? 'sent';
                            @endphp
                            <div
                                wire:key="msg-{{ $msg['id'] }}"
                                class="messenger-bubble-row {{ $isMine ? 'is-mine' : 'is-theirs' }}"
                            >
                                <div class="messenger-bubble">
                                    <p class="messenger-bubble-text">{{ $msg['message'] }}</p>
                                    <div class="messenger-bubble-meta">
                                        <span>{{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}</span>
                                        @if ($isMine)
                                            <span class="messenger-ticks messenger-ticks-{{ $status }}" title="{{ $status }}">
                                                @if ($status === 'read')
                                                    ✓✓
                                                @elseif ($status === 'delivered')
                                                    ✓✓
                                                @else
                                                    ✓
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <footer
                        class="messenger-composer"
                        wire:ignore
                        wire:key="composer-{{ $conversationId }}"
                        x-data="messengerComposer()"
                        @click.outside="closeEmojiPicker()"
                    >
                        <div class="messenger-emoji-wrap">
                            <button
                                type="button"
                                class="messenger-emoji-btn"
                                :class="{ 'is-active': emojiOpen }"
                                @click="toggleEmojiPicker()"
                                aria-label="Emoticon"
                                :aria-expanded="emojiOpen"
                            >
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8 0-.29.02-.58.05-.86 2.36-1.05 4.23-2.98 5.21-5.37C11.07 8.33 14.05 10 17.42 10c.78 0 1.53-.11 2.24-.31.21.74.34 1.53.34 2.35 0 4.41-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                                </svg>
                            </button>
                            <div
                                x-show="emojiOpen"
                                x-cloak
                                x-transition.opacity.duration.150ms
                                class="messenger-emoji-picker"
                                @click.stop
                                role="dialog"
                                aria-label="Pilih emoticon"
                            >
                                <div class="messenger-emoji-tabs" role="tablist">
                                    <template x-for="(category, index) in emojiCategories" :key="category.id">
                                        <button
                                            type="button"
                                            class="messenger-emoji-tab"
                                            :class="{ 'is-active': activeEmojiCategory === index }"
                                            @click="activeEmojiCategory = index"
                                            :aria-selected="activeEmojiCategory === index"
                                            role="tab"
                                            x-text="category.icon"
                                        ></button>
                                    </template>
                                </div>
                                <div class="messenger-emoji-grid" role="listbox">
                                    <template x-for="(emoji, emojiIndex) in activeEmojis" :key="activeEmojiCategory + '-' + emojiIndex">
                                        <button
                                            type="button"
                                            class="messenger-emoji-item"
                                            @click.prevent="insertEmoji(emoji)"
                                            x-text="emoji"
                                            :aria-label="'Sisipkan ' + emoji"
                                        ></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <textarea
                            x-ref="messageInput"
                            x-model="draft"
                            @keydown.enter.prevent="sendMessage()"
                            rows="1"
                            maxlength="2000"
                            placeholder="Ketik pesan"
                            class="messenger-input messenger-textarea"
                            :disabled="sending"
                        ></textarea>
                        <button
                            type="button"
                            @click="sendMessage()"
                            class="messenger-send-btn"
                            :disabled="sending || !draft.trim()"
                            aria-label="Kirim pesan"
                        >
                            <svg viewBox="0 0 18 24" width="20" height="20" fill="currentColor">
                                <path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                        </button>
                    </footer>
                @else
                    <div class="messenger-placeholder">
                        <div class="messenger-placeholder-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="80" height="80" fill="currentColor" opacity="0.25">
                                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
                            </svg>
                        </div>
                        <p class="messenger-placeholder-title">Artha Messenger</p>
                        <p class="messenger-placeholder-text">Dalam tahap pengembangan, fitur akan dikembangkan secara bertahap.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

    @vite(['resources/js/messenger.js'])

    <style>
        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar {
            display: none;
        }

        .messenger-root {
            --wa-green: #00a884;
            --wa-green-dark: #008069;
            --wa-sidebar: #ffffff;
            --wa-header: #f0f2f5;
            --wa-chat-bg: #e2efea;
            --wa-bubble-out: #d9fdd3;
            --wa-bubble-in: #ffffff;
            --wa-border: #e7ecee;
            --wa-text-muted: #667781;
            --wa-hover: #ffffff;
            --wa-active: #ffffff;
            height: calc(100dvh - 2.5rem);
            max-height: 900px;
        }

        .dark .messenger-root {
            --wa-sidebar: #111b21;
            --wa-header: #202c33;
            --wa-chat-bg: #0b141a;
            --wa-bubble-out: #005c4b;
            --wa-bubble-in: #202c33;
            --wa-border: #2a3942;
            --wa-text-muted: #8696a0;
            --wa-hover: #2a3942;
            --wa-active: #2a3942;
        }

        .fi-main { padding-bottom: 0 !important; }

        .messenger-layout {
            display: grid;
            grid-template-columns: minmax(280px, 380px) 1fr;
            gap: 0;
            height: 100%;
            border: 1px solid var(--wa-border);
            border-radius: 8px;
            overflow: hidden;
            background: var(--wa-sidebar);
            box-shadow: 0 1px 3px rgba(11, 20, 26, 0.08);
        }

        .messenger-sidebar {
            display: flex;
            flex-direction: column;
            min-height: 0;
            background: var(--wa-sidebar);
            border-right: 1px solid var(--wa-border);
        }

        .messenger-sidebar-header {
            padding: 10px 16px;
            background: var(--wa-header);
            border-bottom: 1px solid var(--wa-border);
            flex-shrink: 0;
        }

        .messenger-sidebar-title {
            margin: 0;
            font-size: 19px;
            font-weight: 600;
            color: #111b21;
        }

        .dark .messenger-sidebar-title { color: #; }

        .messenger-search {
            position: relative;
            padding: 8px 12px;
            background: var(--wa-header);
            border-bottom: 1px solid var(--wa-border);
            flex-shrink: 0;
        }

        .messenger-search-icon {
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--wa-text-muted);
            pointer-events: none;
            display: flex;
        }

        .messenger-search-input {
            padding-left: 36px !important;
            border-radius: 8px !important;
            border: none !important;
            background: #ffffff !important;
        }

        .dark .messenger-search-input {
            background: #2a3942 !important;
            color: #e9edef !important;
        }

        .messenger-list {
            list-style: none;
            margin: 0;
            padding: 0;
            overflow-y: auto;
            flex: 1;
            min-height: 0;
            background: var(--wa-header);
        }

        .messenger-list-item {
            display: flex;
            gap: 12px;
            padding: 10px 16px;
            cursor: pointer;
            border-bottom: 1px solid transparent;
            transition: background 0.15s ease;
        }

        .messenger-list-item:hover { background: var(--wa-hover); }
        .messenger-list-item.is-active { background: var(--wa-active); }

        .messenger-avatar-wrap { position: relative; flex-shrink: 0; }

        .messenger-avatar {
            width: 49px;
            height: 49px;
            border-radius: 50%;
            object-fit: cover;
        }

        .messenger-avatar-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dfe5e7;
            color: #54656f;
            font-weight: 500;
            font-size: 18px;
        }

        .dark .messenger-avatar-placeholder {
            background: #2a3942;
            color: #aebac1;
        }

        .messenger-presence {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #9ca3af;
            border: 2px solid var(--wa-sidebar);
        }

        .messenger-presence.is-online { background: #25d366; }

        .messenger-list-body { flex: 1; min-width: 0; padding-top: 2px; }

        .messenger-list-top,
        .messenger-list-bottom {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            align-items: center;
        }

        .messenger-list-top { margin-bottom: 2px; }

        .messenger-name {
            font-weight: 400;
            font-size: 16px;
            color: #111b21;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .messenger-name { color: #e9edef; }

        .messenger-time {
            font-size: 12px;
            color: var(--wa-text-muted);
            flex-shrink: 0;
        }

        .messenger-preview {
            font-size: 14px;
            color: var(--wa-text-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
        }

        .messenger-preview-muted { font-style: italic; opacity: 0.85; }

        .messenger-badge {
            background: var(--wa-green);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .messenger-chat {
            display: flex;
            flex-direction: column;
            min-height: 0;
            min-width: 0;
            background: var(--wa-chat-bg);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d4cdc4' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .dark .messenger-chat {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23182229' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .messenger-chat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--wa-header);
            border-bottom: 1px solid var(--wa-border);
            flex-shrink: 0;
        }

        .messenger-chat-header .messenger-avatar {
            width: 40px;
            height: 40px;
        }

        .messenger-back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            margin: 0 -8px 0 -4px;
            padding: 0;
            border: none;
            background: transparent;
            color: var(--wa-text-muted);
            cursor: pointer;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .messenger-back-btn:hover { background: rgba(0, 0, 0, 0.05); }
        .dark .messenger-back-btn:hover { background: rgba(255, 255, 255, 0.08); }

        .messenger-chat-header-info { min-width: 0; flex: 1; }

        .messenger-chat-title {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            color: #111b21;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .messenger-chat-title { color: #e9edef; }

        .messenger-chat-subtitle {
            margin: 0;
            font-size: 13px;
            color: var(--wa-text-muted);
        }

        .messenger-chat-subtitle.is-online {
            color: #25d366;
        }

        .messenger-chat-subtitle.is-offline {
            color: var(--wa-text-muted);
        }

        .messenger-conainer {
            flex: 1;
            overflow-y: auto;
            display: flex;
        }

        .messenger-messages {
            flex: 1;
            overflow-y: auto;
            padding: 12px 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-height: 0;
        }

        .messenger-bubble-row { display: flex; }
        .messenger-bubble-row.is-mine { justify-content: flex-end; }
        .messenger-bubble-row.is-theirs { justify-content: flex-start; }

        .messenger-bubble {
            max-width: min(75%, 520px);
            padding: 6px 8px 4px 10px;
            border-radius: 8px;
            font-size: 14.2px;
            line-height: 1.35;
            box-shadow: 0 1px 0.5px rgba(11, 20, 26, 0.13);
        }

        .messenger-bubble-text {
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .is-mine .messenger-bubble {
            background: var(--wa-bubble-out);
            color: #111b21;
            border-top-right-radius: 0;
        }

        .dark .is-mine .messenger-bubble { color: #e9edef; }

        .is-theirs .messenger-bubble {
            background: var(--wa-bubble-in);
            color: #111b21;
            border-top-left-radius: 0;
        }

        .dark .is-theirs .messenger-bubble { color: #e9edef; }

        .messenger-bubble-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
            margin-top: 2px;
            font-size: 11px;
            color: var(--wa-text-muted);
        }

        .messenger-ticks-read { color: #53bdeb; }

        .messenger-composer {
            display: flex;
            gap: 8px;
            padding: 8px 12px;
            background: var(--wa-header);
            border-top: 1px solid var(--wa-border);
            align-items: flex-end;
            flex-shrink: 0;
            position: relative;
        }

        .messenger-emoji-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .messenger-emoji-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            padding: 0;
            border: none;
            border-radius: 50%;
            background: transparent;
            color: var(--wa-text-muted);
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .messenger-emoji-btn:hover,
        .messenger-emoji-btn.is-active {
            background: var(--wa-hover);
            color: var(--wa-green);
        }

        .messenger-emoji-picker {
            position: absolute;
            bottom: calc(100% + 8px);
            left: 0;
            z-index: 30;
            width: min(320px, calc(100vw - 48px));
            background: #ffffff;
            border: 1px solid var(--wa-border);
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(11, 20, 26, 0.16);
            overflow: hidden;
        }

        .dark .messenger-emoji-picker {
            background: #233138;
            border-color: #2a3942;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
        }

        .messenger-emoji-tabs {
            display: flex;
            gap: 2px;
            padding: 6px 8px;
            border-bottom: 1px solid var(--wa-border);
            overflow-x: auto;
        }

        .messenger-emoji-tab {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: none;
            border-radius: 8px;
            background: transparent;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .messenger-emoji-tab:hover {
            background: var(--wa-hover);
        }

        .messenger-emoji-tab.is-active {
            background: var(--wa-hover);
        }

        .messenger-emoji-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 2px;
            padding: 8px;
            max-height: 220px;
            overflow-y: auto;
        }

        .messenger-emoji-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            aspect-ratio: 1;
            font-size: 22px;
            line-height: 1;
            border: none;
            border-radius: 6px;
            background: transparent;
            cursor: pointer;
            transition: background 0.12s ease, transform 0.12s ease;
        }

        .messenger-emoji-item:hover {
            background: var(--wa-hover);
            transform: scale(1.12);
        }

        .messenger-composer .messenger-textarea {
            flex: 1;
            min-width: 0;
        }

        .messenger-input {
            width: 100%;
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 15px;
            background: #ffffff;
            color: #111b21;
            outline: none;
        }

        .dark .messenger-input {
            background: #2a3942;
            color: #e9edef;
        }

        .messenger-textarea {
            resize: none;
            max-height: 120px;
            line-height: 1.4;
        }

        .messenger-send-btn {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--wa-green);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .messenger-send-btn:hover { background: var(--wa-green-dark); }

        .messenger-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 32px 24px;
            text-align: center;
            background: var(--wa-header);
        }

        .messenger-placeholder-title {
            margin: 16px 0 8px;
            font-size: 28px;
            font-weight: 300;
            color: #41525d;
        }

        .dark .messenger-placeholder-title { color: #e9edef; }

        .messenger-placeholder-text {
            margin: 0;
            font-size: 14px;
            color: var(--wa-text-muted);
            max-width: 420px;
            line-height: 1.5;
        }

        .messenger-empty {
            padding: 32px 16px;
            text-align: center;
            color: var(--wa-text-muted);
            font-size: 14px;
        }

        .messenger-load-more {
            align-self: center;
            margin-bottom: 8px;
            font-size: 12px;
            color: var(--wa-green-dark);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 16px;
        }

        .dark .messenger-load-more { background: #202c33; color: var(--wa-green); }

        @media (max-width: 1024px) {
            .messenger-root {
                height: calc(100dvh - 8rem);
                min-height: 420px;
            }

            .messenger-layout {
                grid-template-columns: minmax(260px, 320px) 1fr;
            }
        }

        @media (max-width: 768px) {
            .messenger-root {
                height: calc(100dvh - 6rem);
                min-height: 0;
                max-height: none;
            }

            .messenger-layout {
                grid-template-columns: 1fr;
                border-radius: 0;
            }

            .messenger-sidebar,
            .messenger-chat {
                grid-column: 1;
                grid-row: 1;
            }

            .messenger-sidebar {
                border-right: none;
                height: 100%;
            }

            .messenger-chat {
                height: 100%;
            }

            .messenger-root:not(.messenger-chat-open) .messenger-chat {
                display: none !important;
            }

            .messenger-root.messenger-chat-open .messenger-sidebar {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .messenger-sidebar-header { padding: 8px 12px; }
            .messenger-search { padding: 6px 8px; }
            .messenger-list-item { padding: 10px 12px; }
            .messenger-avatar { width: 45px; height: 45px; }
            .messenger-bubble { max-width: 88%; }
        }
    </style>

</x-filament-panels::page>

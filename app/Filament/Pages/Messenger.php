<?php

namespace App\Filament\Pages;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\Messenger\ConversationService;
use App\Services\Messenger\MessengerService;
use App\Support\Messenger\MessengerBroadcastConfig;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class Messenger extends Page
{
    use AuthorizesRequests;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Messenger 1.1 Beta';

    protected static ?int $navigationSort = 50;

    public string $userSearch = '';

    public ?int $selectedUserId = null;

    public ?int $conversationId = null;

    public bool $selectedUserOnline = false;

    public string $messageBody = '';

    /** @var array<int, array<string, mixed>> */
    public array $messages = [];

    /** @var array<int, array<string, mixed>> */
    public array $conversations = [];

    /** @var array<int, array<string, mixed>> */
    public array $users = [];

    /** @var array<int, array<string, mixed>> */
    public array $chatList = [];

    public bool $hasMoreMessages = false;

    public function mount(MessengerService $messenger): void
    {
        $messenger->touchPresence(Auth::user());
        $this->loadConversations($messenger);
        $this->loadUsers($messenger);
    }

    // public static function getNavigationGroup(): ?string
    // {
    //     return 'System';
    // }

    public static function canAccess(): bool
    {
        return Auth::check();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getNavigationBadgeCount();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    #[Computed]
    public function navigationBadgeCount(): ?string
    {
        return static::getNavigationBadgeCount();
    }

    protected static function getNavigationBadgeCount(): ?string
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $count = app(MessengerService::class)->totalUnreadForUser($user);

        return $count > 0 ? (string) $count : null;
    }

    #[On('refresh-navigation-badge')]
    public function refreshNavigationBadge(): void
    {
        unset($this->navigationBadgeCount);

        $this->dispatch('$refresh');
    }

    protected function refreshSelectedUserPresence(MessengerService $messenger): void
    {
        $this->selectedUserOnline = $this->selectedUserId
            ? $messenger->isOnline($this->selectedUserId)
            : false;
    }

    protected function refreshNavigationBadgeFromMessenger(): void
    {
        $this->dispatch('refresh-navigation-badge');
    }

    public function getView(): string
    {
        return 'filament.pages.messenger';
    }

    public function getBroadcastConfig(): array
    {
        return MessengerBroadcastConfig::resolve(Auth::user());
    }

    public function loadConversations(MessengerService $messenger): void
    {
        $userId = Auth::id();

        $search = trim($this->userSearch);
        $searchLower = $search !== '' ? mb_strtolower($search) : null;

        $this->conversations = Conversation::query()
            ->whereHas('participants', fn ($q) => $q->where('user_id', $userId))
            ->with(['latestMessage.sender', 'participants.user'])
            ->orderByDesc('last_message_at')
            ->get()
            ->when($searchLower !== null, function (Collection $collection) use ($userId, $searchLower) {
                return $collection->filter(function (Conversation $conversation) use ($userId, $searchLower) {
                    $other = $conversation->participants
                        ->first(fn ($p) => $p->user_id !== $userId)?->user;

                    if (! $other) {
                        return false;
                    }

                    return str_contains(mb_strtolower($other->name), $searchLower)
                        || str_contains(mb_strtolower((string) $other->username), $searchLower)
                        || str_contains(mb_strtolower($other->email), $searchLower);
                });
            })
            ->map(function (Conversation $conversation) use ($userId, $messenger) {
                $other = $conversation->participants
                    ->first(fn ($p) => $p->user_id !== $userId)?->user;

                $latest = $conversation->latestMessage;

                return [
                    'id' => $conversation->id,
                    'other_user_id' => $other?->id,
                    'other_name' => $other?->name ?? 'Unknown',
                    'other_image_url' => $other?->messengerAvatarUrl(),
                    'online' => $other ? $messenger->isOnline($other->id) : false,
                    'last_message' => $latest?->message,
                    'last_message_at' => $latest?->created_at?->diffForHumans(),
                    'unread_count' => $messenger->unreadCountForUser(Auth::user(), $conversation),
                ];
            })
            ->values()
            ->all();

        $this->refreshChatList();
    }

    public function loadUsers(MessengerService $messenger): void
    {
        $query = User::query()
            ->where('id', '!=', Auth::id())
            ->orderBy('name');

        if (trim($this->userSearch) !== '') {
            $term = '%'.trim($this->userSearch).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('username', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        $this->users = $query->limit(50)->get()->map(fn (User $user) => [
            'id' => $user->id,
            'name' => $user->name,
            'image_url' => $user->messengerAvatarUrl(),
            'online' => $messenger->isOnline($user->id),
        ])->all();

        $this->refreshChatList();
    }

    protected function refreshChatList(): void
    {
        $conversationUserIds = collect($this->conversations)
            ->pluck('other_user_id')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $list = [];

        foreach ($this->conversations as $conv) {
            $list[] = [
                'kind' => 'conversation',
                'conversation_id' => $conv['id'],
                'user_id' => $conv['other_user_id'],
                'name' => $conv['other_name'],
                'image_url' => $conv['other_image_url'],
                'online' => $conv['online'],
                'last_message' => $conv['last_message'],
                'last_message_at' => $conv['last_message_at'],
                'unread_count' => $conv['unread_count'],
            ];
        }

        foreach ($this->users as $user) {
            if ($conversationUserIds->contains((int) $user['id'])) {
                continue;
            }

            $list[] = [
                'kind' => 'user',
                'conversation_id' => null,
                'user_id' => $user['id'],
                'name' => $user['name'],
                'image_url' => $user['image_url'],
                'online' => $user['online'],
                'last_message' => null,
                'last_message_at' => null,
                'unread_count' => 0,
            ];
        }

        $this->chatList = $list;
    }

    public function updatedUserSearch(): void
    {
        $this->loadUsers(app(MessengerService::class));
    }

    public function selectUser(int $userId, ConversationService $conversationService, MessengerService $messenger): void
    {
        $other = User::findOrFail($userId);
        $conversation = $conversationService->findOrCreateDirectConversation(Auth::user(), $other);

        $this->selectedUserId = $userId;
        $this->conversationId = $conversation->id;
        $this->openConversation($messenger);
    }

    public function selectConversation(int $conversationId, MessengerService $messenger): void
    {
        $conversation = Conversation::findOrFail($conversationId);
        $this->authorize('view', $conversation);

        $other = $conversation->participants
            ->first(fn ($p) => $p->user_id !== Auth::id())?->user;

        $this->conversationId = $conversation->id;
        $this->selectedUserId = $other?->id;
        $this->openConversation($messenger);
    }

    protected function openConversation(MessengerService $messenger): void
    {
        if (! $this->conversationId) {
            return;
        }

        $conversation = Conversation::findOrFail($this->conversationId);
        $this->authorize('view', $conversation);

        $limit = (int) config('messenger.messages_per_page', 50);
        $collection = $messenger->getMessages($conversation, null, $limit + 1);

        $this->hasMoreMessages = $collection->count() > $limit;
        $messages = $collection->take($limit);

        $this->messages = $messages->map(fn (Message $m) => MessageSent::formatMessage($m))->all();

        $messenger->markConversationRead($conversation, Auth::user());
        $this->refreshSelectedUserPresence($messenger);
        $this->loadConversations($messenger);
        $this->refreshNavigationBadgeFromMessenger();
        $this->dispatch('messenger-scroll-bottom');
    }

    public function sendMessage(MessengerService $messenger, string $body = ''): void
    {
        if (! $this->conversationId) {
            return;
        }

        $text = trim($body !== '' ? $body : $this->messageBody);

        if ($text === '') {
            return;
        }

        $conversation = Conversation::findOrFail($this->conversationId);
        $this->authorize('view', $conversation);

        try {
            $message = $messenger->sendMessage($conversation, Auth::user(), $text);
            $this->messages[] = MessageSent::formatMessage($message);
            $this->messageBody = '';
            $this->loadConversations($messenger);
            $this->refreshNavigationBadgeFromMessenger();
            $this->dispatch('messenger-scroll-bottom');
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal mengirim pesan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function loadOlderMessages(MessengerService $messenger): void
    {
        if (! $this->conversationId || empty($this->messages)) {
            return;
        }

        $conversation = Conversation::findOrFail($this->conversationId);
        $this->authorize('view', $conversation);

        $firstId = $this->messages[0]['id'] ?? null;
        if (! $firstId) {
            return;
        }

        $limit = (int) config('messenger.messages_per_page', 50);
        $collection = $messenger->getMessages($conversation, $firstId, $limit + 1);
        $this->hasMoreMessages = $collection->count() > $limit;
        $older = $collection->take($limit)->map(fn (Message $m) => MessageSent::formatMessage($m))->all();

        $this->messages = array_values(array_merge($older, $this->messages));
    }

    public function heartbeat(MessengerService $messenger): void
    {
        $messenger->touchPresence(Auth::user());
        $this->refreshSelectedUserPresence($messenger);
        $this->loadConversations($messenger);
        $this->loadUsers($messenger);
    }

    public function pollRefresh(MessengerService $messenger): void
    {
        $messenger->touchPresence(Auth::user());

        if ($this->conversationId) {
            $conversation = Conversation::find($this->conversationId);
            if ($conversation) {
                $latestId = (int) $conversation->messages()->max('id');
                $currentLastId = (int) (collect($this->messages)->last()['id'] ?? 0);

                if ($latestId > $currentLastId) {
                    $latest = $messenger->getMessages($conversation, null, (int) config('messenger.messages_per_page', 50));
                    $this->messages = $latest->map(fn (Message $m) => MessageSent::formatMessage($m))->all();
                    $messenger->markConversationRead($conversation, Auth::user());
                    $this->dispatch('messenger-scroll-bottom');
                }
            }
        }

        $this->refreshSelectedUserPresence($messenger);
        $this->loadConversations($messenger);
        $this->loadUsers($messenger);
        $this->refreshNavigationBadgeFromMessenger();
    }

    #[On('messenger-message-received')]
    public function onMessageReceived(mixed $message, MessengerService $messenger): void
    {
        $message = $this->normalizeMessengerEventPayload($message);

        if ($message === []) {
            return;
        }

        if ((int) ($message['conversation_id'] ?? 0) !== (int) $this->conversationId) {
            $this->loadConversations($messenger);
            $this->refreshNavigationBadgeFromMessenger();

            return;
        }

        $exists = collect($this->messages)->contains('id', $message['id']);
        if (! $exists) {
            $this->messages[] = $message;
            $this->dispatch('messenger-scroll-bottom');
        }

        $dbMessage = Message::find($message['id']);
        if ($dbMessage) {
            $messenger->markDelivered($dbMessage, Auth::user());
            $messenger->markConversationRead($dbMessage->conversation, Auth::user());
        }

        $this->refreshSelectedUserPresence($messenger);
        $this->loadConversations($messenger);
        $this->refreshNavigationBadgeFromMessenger();
    }

    #[On('messenger-message-delivered')]
    public function onMessageDelivered(mixed $payload): void
    {
        $payload = $this->normalizeMessengerEventPayload($payload);

        if ($payload === []) {
            return;
        }

        $this->updateMessageInList((int) $payload['message_id'], [
            'delivered_at' => $payload['delivered_at'],
            'read_status' => 'delivered',
        ]);
    }

    #[On('messenger-message-read')]
    public function onMessageRead(mixed $payload): void
    {
        $payload = $this->normalizeMessengerEventPayload($payload);

        if ($payload === []) {
            return;
        }

        $this->updateMessageInList((int) $payload['message_id'], [
            'read_at' => $payload['read_at'],
            'read_status' => 'read',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizeMessengerEventPayload(mixed $message): array
    {
        if (is_string($message)) {
            $decoded = json_decode($message, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($message) ? $message : [];
    }

    protected function updateMessageInList(int $messageId, array $changes): void
    {
        foreach ($this->messages as $index => $msg) {
            if ((int) $msg['id'] === $messageId) {
                $this->messages[$index] = array_merge($msg, $changes);
            }
        }
    }

    public function getSelectedUser(): ?User
    {
        if (! $this->selectedUserId) {
            return null;
        }

        return User::find($this->selectedUserId);
    }

}

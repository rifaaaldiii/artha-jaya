<?php

namespace App\Services\Messenger;

use App\Events\MessageDelivered;
use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessengerAuditLog;
use App\Models\User;
use App\Notifications\NewMessengerMessageNotification;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MessengerService
{
    public function __construct(
        protected ConversationService $conversationService,
    ) {}

    public function sendMessage(Conversation $conversation, User $sender, string $body): Message
    {
        $body = trim($body);
        $maxLength = (int) config('messenger.message_max_length', 2000);

        if ($body === '' || mb_strlen($body) > $maxLength) {
            throw new \InvalidArgumentException('Invalid message body.');
        }

        if (! $conversation->hasParticipant($sender->id)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('You are not a participant of this conversation.');
        }

        return DB::transaction(function () use ($conversation, $sender, $body) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'message' => $body,
                'created_at' => now(),
            ]);

            $conversation->update(['last_message_at' => $message->created_at]);

            MessengerAuditLog::create([
                'user_id' => $sender->id,
                'conversation_id' => $conversation->id,
                'action' => 'message_sent',
                'meta' => ['message_id' => $message->id],
                'created_at' => now(),
            ]);

            broadcast(new MessageSent($message))->toOthers();

            $recipients = $conversation->participants()
                ->where('user_id', '!=', $sender->id)
                ->with('user')
                ->get();

            foreach ($recipients as $participant) {
                if ($participant->user) {
                    $participant->user->notify(new NewMessengerMessageNotification($message, $sender));
                }
            }

            PollTriggerStore::bump(PollChannel::NAVIGATION_BADGE);

            return $message->load('sender');
        });
    }

    public function markDelivered(Message $message, User $recipient): void
    {
        if ($message->sender_id === $recipient->id) {
            return;
        }

        if (! $message->conversation->hasParticipant($recipient->id)) {
            return;
        }

        if ($message->delivered_at) {
            return;
        }

        $message->update(['delivered_at' => now()]);
        broadcast(new MessageDelivered($message))->toOthers();
    }

    public function markConversationRead(Conversation $conversation, User $reader): void
    {
        if (! $conversation->hasParticipant($reader->id)) {
            return;
        }

        DB::transaction(function () use ($conversation, $reader) {
            $conversation->participants()
                ->where('user_id', $reader->id)
                ->update(['last_read_at' => now()]);

            $unreadMessages = $conversation->messages()
                ->where('sender_id', '!=', $reader->id)
                ->whereNull('read_at')
                ->get();

            foreach ($unreadMessages as $message) {
                $message->update(['read_at' => now()]);
                broadcast(new MessageRead($message))->toOthers();
            }

            if ($unreadMessages->isNotEmpty()) {
                MessengerAuditLog::create([
                    'user_id' => $reader->id,
                    'conversation_id' => $conversation->id,
                    'action' => 'message_read',
                    'meta' => ['message_ids' => $unreadMessages->pluck('id')->all()],
                    'created_at' => now(),
                ]);
            }
        });
    }

    public function getMessages(Conversation $conversation, ?int $beforeId = null, int $limit = 50): Collection
    {
        $query = $conversation->messages()
            ->with('sender')
            ->orderByDesc('id');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        return $query->limit($limit)->get()->reverse()->values();
    }

    public function unreadCountForUser(User $user, Conversation $conversation): int
    {
        $participant = $conversation->participants()->where('user_id', $user->id)->first();
        $lastRead = $participant?->last_read_at;

        return $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
            ->count();
    }

    public function totalUnreadForUser(User $user): int
    {
        $conversationIds = $user->conversationParticipants()->pluck('conversation_id');

        if ($conversationIds->isEmpty()) {
            return 0;
        }

        $total = 0;

        foreach (Conversation::whereIn('id', $conversationIds)->get() as $conversation) {
            $total += $this->unreadCountForUser($user, $conversation);
        }

        return $total;
    }

    public function touchPresence(User $user): void
    {
        $ttl = (int) config('messenger.presence_ttl_seconds', 120);
        Cache::put($this->presenceKey($user->id), now()->timestamp, $ttl);
    }

    public function clearPresence(User $user): void
    {
        Cache::forget($this->presenceKey($user->id));
    }

    public function isOnline(int $userId): bool
    {
        return Cache::has($this->presenceKey($userId));
    }

    protected function presenceKey(int $userId): string
    {
        return 'messenger:online:'.$userId;
    }
}

<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use App\Services\Messenger\MessengerService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class MessengerIncomingMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message,
        public User $recipient,
    ) {
        $this->message->loadMissing('sender');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->recipient->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'messenger.incoming';
    }

    public function broadcastWith(): array
    {
        $totalUnread = app(MessengerService::class)->totalUnreadForUser($this->recipient);

        return [
            'message' => MessageSent::formatMessage($this->message),
            'sender_name' => $this->message->sender?->name ?? 'Pengguna',
            'preview' => Str::limit($this->message->message, 120),
            'conversation_id' => $this->message->conversation_id,
            'total_unread' => $totalUnread,
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessengerMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message,
        public User $sender,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Pesan baru dari '.$this->sender->name)
            ->body(Str::limit($this->message->message, 120))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->getDatabaseMessage();
    }
}

<?php

namespace App\Services\Messenger;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\MessengerAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ConversationService
{
    public function findOrCreateDirectConversation(User $currentUser, User $otherUser): Conversation
    {
        if ($currentUser->id === $otherUser->id) {
            throw new \InvalidArgumentException('Cannot start a conversation with yourself.');
        }

        $existing = Conversation::query()
            ->whereHas('participants', fn ($q) => $q->where('user_id', $currentUser->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $otherUser->id))
            ->withCount('participants')
            ->having('participants_count', '=', 2)
            ->first();

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($currentUser, $otherUser) {
            $conversation = Conversation::create();

            foreach ([$currentUser->id, $otherUser->id] as $userId) {
                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $userId,
                ]);
            }

            MessengerAuditLog::create([
                'user_id' => $currentUser->id,
                'conversation_id' => $conversation->id,
                'action' => 'conversation_created',
                'meta' => ['participant_ids' => [$currentUser->id, $otherUser->id]],
                'created_at' => now(),
            ]);

            return $conversation;
        });
    }
}

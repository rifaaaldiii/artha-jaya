<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'kontak',
        'image',
        'password',
        'role',
        'branch',
        'createdAt',
        'UpdateAt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'createdAt' => 'datetime',
            'UpdateAt' => 'datetime',
        ];
    }

    /**
     * Get the full URL for the profile image
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        try {
            $filename = ltrim(basename((string) $this->image), '/');
            if ($filename === '') {
                return null;
            }

            $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
            $url = $baseUrl.'/profile-images/users/'.$filename;

            return preg_replace('#([^:])//+#', '$1/', $url);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function conversationParticipants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->withPivot(['last_read_at'])
            ->withTimestamps();
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messengerAvatarUrl(): ?string
    {
        return $this->profile_image_url;
    }

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (blank($user->createdAt)) {
                $user->createdAt = now();
            }
        });

        static::updating(function (User $user): void {
            $user->UpdateAt = now();
        });
    }
}

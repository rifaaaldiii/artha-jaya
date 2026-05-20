<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        if (!$this->image) {
            return null;
        }

        try {
            $cleanPath = ltrim($this->image, '/');
            $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
            $url = $baseUrl . '/profile-images/' . $cleanPath;
            
            return preg_replace('#([^:])//+#', '$1/', $url);
        } catch (\Exception $e) {
            return null;
        }
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

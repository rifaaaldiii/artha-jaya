<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Database\Factories\PetukangFactory factory($count = null, $state = [])
 */
class petukang extends Model
{
    /** @use HasFactory<\Database\Factories\PetukangFactory> */
    use HasFactory;
    protected $table = 'petukangs';

    protected $fillable = [
        'nama',
        'status',
        'kontak',
        'team_id',
        'createdAt',
        'updateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'updateAt' => 'datetime',
    ];

    /**
     * Get the team for this petukang.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(team::class, 'team_id');
    }

    protected static function booted(): void
    {
        static::creating(function (petukang $petukang): void {
            if (blank($petukang->createdAt)) {
                $petukang->createdAt = now();
            }
        });

        static::updating(function (petukang $petukang): void {
            $petukang->updateAt = now();
        });
    }
}

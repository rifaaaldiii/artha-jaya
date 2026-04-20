<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
        'createdAt',
        'UpdateAt',
    ];

    public $timestamps = false;

    protected $casts = [
        'createdAt' => 'datetime',
        'UpdateAt' => 'datetime',
    ];

    /**
     * Get the team for this customer.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get all jasas for this customer.
     */
    public function jasas()
    {
        return $this->hasMany(Jasa::class, 'pelanggan_id');
    }

    /**
     * Get all produksis for this customer.
     */
    public function produksis()
    {
        return $this->hasMany(Produksi::class, 'pelanggan_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Customer $customer): void {
            if (blank($customer->createdAt)) {
                $customer->createdAt = now();
            }
        });

        static::updating(function (Customer $customer): void {
            $customer->UpdateAt = now();
        });
    }
}

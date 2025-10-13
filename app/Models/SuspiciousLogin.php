<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspiciousLogin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'login_latitude',
        'login_longitude',
        'allowed_latitude',
        'allowed_longitude',
        'distance_km',
        'ip_address',
        'user_agent',
        'notified',
        'notified_at',
    ];

    protected $casts = [
        'login_latitude' => 'float',
        'login_longitude' => 'float',
        'allowed_latitude' => 'float',
        'allowed_longitude' => 'float',
        'distance_km' => 'float',
        'notified' => 'boolean',
        'notified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnnotified($query)
    {
        return $query->where('notified', false);
    }

    public function markAsNotified(): bool
    {
        return $this->update([
            'notified' => true,
            'notified_at' => now(),
        ]);
    }
}
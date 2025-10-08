<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, Notifiable, LogsActivity {
        LogsActivity::bootLogsActivity as parentBootLogsActivity;
    }

    /**
     * The statuses that a ticket can have.
     *
     * @var array
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_READY = 'ready';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all activities for the ticket.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(\App\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class)
            ->latest();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * Get the public URL for this ticket.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('tickets.public.show', $this->public_token);
    }

    /**
     * Get the options for the activity logger.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'check_out_at', 'delivered_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ticket')
            ->setDescriptionForEvent(function(string $eventName) {
                return "Ticket has been {$eventName}";
            });
    }

    protected $fillable = [
        'uuid',
        'ticket_number',
        'public_token',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'vehicle_make',
        'vehicle_model',
        'vehicle_color',
        'license_plate',
        'parking_spot',
        'parking_zone',
        'special_instructions',
        'damage_notes',
        'check_in_at',
        'check_out_at',
        'ready_at',
        'delivered_at',
        'amount',
        'payment_status',
        'payment_method',
        'payment_reference',
        'qr_code_path',
        'verification_code',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'tenant_id',
        'assigned_to',
        'created_by',
        'delivered_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
        'amount' => 'decimal:2',
        'check_in_latitude' => 'float',
        'check_in_longitude' => 'float',
        'check_out_latitude' => 'float',
        'check_out_longitude' => 'float',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status_label',
        'qr_code_url',
        'is_active',
        'public_url',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($ticket) {
            if (empty($ticket->uuid)) {
                $ticket->uuid = (string) Str::uuid();
            }
            
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = static::generateTicketNumber();
            }
            
            if (empty($ticket->verification_code)) {
                $ticket->verification_code = strtoupper(Str::random(6));
            }
            
            if (empty($ticket->check_in_at)) {
                $ticket->check_in_at = now();
            }
            
            if (empty($ticket->public_token)) {
                $ticket->public_token = Str::random(32);
            }
        });
    }

    /**
     * Generate a unique ticket number.
     *
     * @return string
     */
    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Get the status label attribute.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_READY => 'Ready for Pickup',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ][$this->status] ?? 'Unknown';
    }

    /**
     * Get the QR code URL attribute.
     *
     * @return string|null
     */
    public function getQrCodeUrlAttribute()
    {
        if (!$this->qr_code_path) {
            return null;
        }

        if (filter_var($this->qr_code_path, FILTER_VALIDATE_URL)) {
            return $this->qr_code_path;
        }

        return \Storage::disk('public')->url($this->qr_code_path);
    }

    /**
     * Get the is active attribute.
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_READY,
        ]);
    }

    /**
     * Get the tenant that owns the ticket.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user who created the ticket.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who delivered the ticket.
     */
    public function deliverer()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    /**
     * Get the images for the ticket.
     */
    public function images()
    {
        return $this->hasMany(TicketImage::class);
    }

    /**
     * Scope a query to only include active tickets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_READY,
        ]);
    }

    /**
     * Scope a query to only include tickets for a specific tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $tenantId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Mark the ticket as in progress.
     *
     * @return bool
     */
    public function markAsInProgress()
    {
        return $this->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    /**
     * Mark the ticket as ready.
     *
     * @return bool
     */
    public function markAsReady()
    {
        return $this->update([
            'status' => self::STATUS_READY,
            'ready_at' => $this->ready_at ?? now(),
        ]);
    }

    /**
     * Mark the ticket as delivered.
     *
     * @param  int  $userId
     * @return bool
     */
    public function markAsDelivered($userId)
    {
        return $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_by' => $userId,
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark the ticket as cancelled.
     *
     * @return bool
     */
    public function markAsCancelled()
    {
        return $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /**
     * Check if the ticket is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the ticket is in progress.
     *
     * @return bool
     */
    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if the ticket is ready for pickup.
     *
     * @return bool
     */
    public function isReady()
    {
        return $this->status === self::STATUS_READY;
    }

    /**
     * Check if the ticket has been delivered.
     *
     * @return bool
     */
    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Check if the ticket has been cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}

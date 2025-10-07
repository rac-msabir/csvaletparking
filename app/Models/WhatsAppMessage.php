<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessage extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'ticket_id',
        'tenant_id',
        'customer_phone',
        'customer_name',
        'customer_email',
        'content',
        'message_type',
        'template_name',
        'status',
        'error_message',
        'attempts',
        'message_sid',
        'account_sid',
        'twilio_response',
        'media_urls',
        'cost',
        'sent_at',
        'delivered_at',
        'read_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'media_urls' => 'array',
        'twilio_response' => 'array',
        'cost' => 'decimal:4',
        'attempts' => 'integer'
    ];

    protected $dates = [
        'sent_at',
        'delivered_at',
        'read_at'
    ];

    /**
     * Get the ticket that owns the message.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the tenant that owns the message.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope a query to only include pending messages.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include failed messages.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark the message as sent.
     */
    public function markAsSent($messageSid = null, $twilioResponse = null)
    {
        $this->update([
            'status' => 'sent',
            'message_sid' => $messageSid,
            'twilio_response' => $twilioResponse,
            'sent_at' => now()
        ]);
    }

    /**
     * Mark the message as delivered.
     */
    public function markAsDelivered($twilioResponse = null)
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'twilio_response' => $twilioResponse ?: $this->twilio_response
        ]);
    }

    /**
     * Mark the message as failed.
     */
    public function markAsFailed($errorMessage = null, $twilioResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'twilio_response' => $twilioResponse ?: $this->twilio_response,
            'attempts' => $this->attempts + 1
        ]);
    }
}

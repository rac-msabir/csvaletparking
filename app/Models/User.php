<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'phone',
        'job_title',
        'timezone',
        'locale',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'is_online',
        'initials',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->timezone)) {
                $user->timezone = config('app.timezone', 'UTC');
            }
            if (empty($user->locale)) {
                $user->locale = config('app.locale', 'en');
            }
        });

        static::deleting(function ($user) {
            if (! $user->isForceDeleting()) {
                return;
            }
            // Handle soft-delete related models if needed
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'settings' => 'array',
        ]);
    }

    /**
     * Get the tenant that the user belongs to.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get all tickets created by this user.
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    /**
     * Get all tickets delivered by this user.
     */
    public function deliveredTickets()
    {
        return $this->hasMany(Ticket::class, 'delivered_by');
    }

    /**
     * Get all ticket images uploaded by this user.
     */
    public function uploadedImages()
    {
        return $this->hasMany(TicketImage::class, 'uploaded_by');
    }

    /**
     * Get the user's initials.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->name;
        $initials = '';
        
        $words = explode(' ', $name);
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            if (strlen($initials) >= 2) {
                break;
            }
        }
        
        return $initials;
    }

    /**
     * Check if the user is online.
     */
    public function getIsOnlineAttribute(): bool
    {
        return Cache::has('user-online-' . $this->id);
    }

    /**
     * Get the user's timezone.
     */
    public function getTimezoneAttribute($value): string
    {
        return $value ?? config('app.timezone', 'UTC');
    }

    /**
     * Get the user's locale.
     */
    public function getLocaleAttribute($value): string
    {
        return $value ?? config('app.locale', 'en');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include users of a specific tenant.
     */
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? tenant('id'));
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->email === config('app.super_admin_email');
    }

    /**
     * Check if the user is an admin of their tenant.
     */
    public function isTenantAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get the user's role names.
     */
    public function getRoleNamesAttribute()
    {
        return $this->roles->pluck('name');
    }

    /**
     * Get the user's permission names.
     */
    public function getPermissionNamesAttribute()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    /**
     * Get the user's settings.
     */
    public function getSetting($key, $default = null)
    {
        $settings = $this->settings ?? [];
        return $settings[$key] ?? $default;
    }

    /**
     * Set a user setting.
     */
    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
        return $this->save();
    }
}
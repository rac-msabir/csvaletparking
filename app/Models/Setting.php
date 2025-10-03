<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The types of settings.
     *
     * @var array
     */
    public const TYPE_STRING = 'string';
    public const TYPE_NUMBER = 'number';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_JSON = 'json';
    public const TYPE_TEXT = 'text';
    public const TYPE_HTML = 'html';
    public const TYPE_DATE = 'date';
    public const TYPE_DATETIME = 'datetime';
    public const TYPE_TIME = 'time';

    /**
     * The groups for settings.
     *
     * @var array
     */
    public const GROUP_GENERAL = 'general';
    public const GROUP_EMAIL = 'email';
    public const GROUP_NOTIFICATIONS = 'notifications';
    public const GROUP_PAYMENT = 'payment';
    public const GROUP_INTEGRATIONS = 'integrations';
    public const GROUP_APPEARANCE = 'appearance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_encrypted',
        'is_public',
        'is_cached',
        'is_editable',
        'is_visible',
        'is_tenant_setting',
        'is_user_setting',
        'is_team_setting',
        'is_role_setting',
        'is_permission_setting',
        'model_type',
        'model_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_public' => 'boolean',
        'is_cached' => 'boolean',
        'is_editable' => 'boolean',
        'is_visible' => 'boolean',
        'is_tenant_setting' => 'boolean',
        'is_user_setting' => 'boolean',
        'is_team_setting' => 'boolean',
        'is_role_setting' => 'boolean',
        'is_permission_setting' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'type_label',
        'group_label',
        'is_encrypted_value',
    ];

    /**
     * Get the type label attribute.
     *
     * @return string
     */
    public function getTypeLabelAttribute()
    {
        return [
            self::TYPE_STRING => 'Text',
            self::TYPE_NUMBER => 'Number',
            self::TYPE_BOOLEAN => 'Yes/No',
            self::TYPE_JSON => 'JSON',
            self::TYPE_TEXT => 'Long Text',
            self::TYPE_HTML => 'HTML',
            self::TYPE_DATE => 'Date',
            self::TYPE_DATETIME => 'Date & Time',
            self::TYPE_TIME => 'Time',
        ][$this->type] ?? 'Unknown';
    }

    /**
     * Get the group label attribute.
     *
     * @return string
     */
    public function getGroupLabelAttribute()
    {
        return [
            self::GROUP_GENERAL => 'General',
            self::GROUP_EMAIL => 'Email',
            self::GROUP_NOTIFICATIONS => 'Notifications',
            self::GROUP_PAYMENT => 'Payment',
            self::GROUP_INTEGRATIONS => 'Integrations',
            self::GROUP_APPEARANCE => 'Appearance',
        ][$this->group] ?? ucfirst(str_replace('_', ' ', $this->group));
    }

    /**
     * Get the decrypted value attribute.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        if ($this->is_encrypted && $value) {
            try {
                return decrypt($value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }

    /**
     * Set the encrypted value attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        if ($this->is_encrypted && $value !== null) {
            $value = encrypt($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get the is encrypted value attribute.
     *
     * @return bool
     */
    public function getIsEncryptedValueAttribute()
    {
        return $this->is_encrypted;
    }

    /**
     * Get the tenant that owns the setting.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the owning model.
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include settings for a specific tenant.
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
     * Scope a query to only include settings for a specific group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope a query to only include public settings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $public
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query, $public = true)
    {
        return $query->where('is_public', $public);
    }

    /**
     * Scope a query to only include editable settings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $editable
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEditable($query, $editable = true)
    {
        return $query->where('is_editable', $editable);
    }

    /**
     * Get a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @param  int|null  $tenantId
     * @return mixed
     */
    public static function getValue($key, $default = null, $tenantId = null)
    {
        $cacheKey = "setting.{$key}." . ($tenantId ?: 'global');
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey, $default);
        }

        $query = static::where('key', $key);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        } else {
            $query->whereNull('tenant_id');
        }

        $setting = $query->first();

        if (!$setting) {
            return $default;
        }

        if ($setting->is_cached) {
            Cache::put($cacheKey, $setting->value, now()->addDay());
        }

        return $setting->value;
    }

    /**
     * Set a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int|null  $tenantId
     * @param  array  $options
     * @return \App\Models\Setting
     */
    public static function setValue($key, $value, $tenantId = null, array $options = [])
    {
        $setting = static::updateOrCreate(
            [
                'key' => $key,
                'tenant_id' => $tenantId,
            ],
            array_merge($options, [
                'value' => $value,
            ])
        );

        // Clear the cache for this setting
        Cache::forget("setting.{$key}." . ($tenantId ?: 'global'));

        return $setting;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($setting) {
            // Clear the cache for this setting when it's updated
            \Illuminate\Support\Facades\Cache::forget("setting.{$setting->key}." . ($setting->tenant_id ?: 'global'));
        });

        static::deleted(function ($setting) {
            // Clear the cache for this setting when it's deleted
            \Illuminate\Support\Facades\Cache::forget("setting.{$setting->key}." . ($setting->tenant_id ?: 'global'));
        });
    }
}

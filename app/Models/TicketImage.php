<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketImage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The types of images that can be uploaded.
     *
     * @var array
     */
    public const TYPE_VEHICLE = 'vehicle';
    public const TYPE_DAMAGE = 'damage';
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_OTHER = 'other';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'uploaded_by',
        'path',
        'original_name',
        'mime_type',
        'size',
        'image_type',
        'description',
        'is_processed',
        'metadata',
        'thumbnail_path',
        'medium_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_processed' => 'boolean',
        'size' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'thumbnail_url',
        'medium_url',
    ];

    /**
     * Get the URL to the image.
     *
     * @return string|null
     */
    public function getUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }

        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            return $this->path;
        }

        return $this->getFileUrl($this->path);
    }

    /**
     * Get the URL to the thumbnail image.
     *
     * @return string|null
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail_path) {
            return $this->url;
        }

        if (filter_var($this->thumbnail_path, FILTER_VALIDATE_URL)) {
            return $this->thumbnail_path;
        }

        return $this->getFileUrl($this->thumbnail_path);
    }

    /**
     * Get the URL to the medium-sized image.
     *
     * @return string|null
     */
    public function getMediumUrlAttribute()
    {
        if (!$this->medium_path) {
            return $this->url;
        }

        if (filter_var($this->medium_path, FILTER_VALIDATE_URL)) {
            return $this->medium_path;
        }

        return $this->getFileUrl($this->medium_path);
    }

    /**
     * Get the file URL from storage.
     *
     * @param  string  $path
     * @return string
     */
    protected function getFileUrl($path)
    {
        $disk = config('filesystems.default');
        
        if ($disk === 's3') {
            return \Storage::disk('s3')->url($path);
        }
        
        return \Storage::url($path);
    }

    /**
     * Get the image type label.
     *
     * @return string
     */
    public function getImageTypeLabelAttribute()
    {
        return [
            self::TYPE_VEHICLE => 'Vehicle',
            self::TYPE_DAMAGE => 'Damage',
            self::TYPE_DOCUMENT => 'Document',
            self::TYPE_OTHER => 'Other',
        ][$this->image_type] ?? 'Unknown';
    }

    /**
     * Get the file size in a human-readable format.
     *
     * @return string
     */
    public function getFormattedSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = $this->size;
        $precision = 2;
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get the ticket that owns the image.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who uploaded the image.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope a query to only include images of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('image_type', $type);
    }

    /**
     * Scope a query to only include processed images.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $processed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProcessed($query, $processed = true)
    {
        return $query->where('is_processed', $processed);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($image) {
            // If soft deleting, don't delete the actual file
            if ($image->isForceDeleting()) {
                // Delete the file from S3 storage
                if ($image->path) {
                    \Storage::disk('s3')->delete($image->path);
                }
                
                // Delete the thumbnail if it exists
                if ($image->thumbnail_path) {
                    \Storage::disk('s3')->delete($image->thumbnail_path);
                }
                
                // Delete the medium image if it exists
                if ($image->medium_path) {
                    \Storage::disk('s3')->delete($image->medium_path);
                }
            }
        });
    }
}

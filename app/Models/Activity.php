<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'collection',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * Get the user that triggered the activity.
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Get the parent subject model (ticket, user, etc.).
     */
    public function subject()
    {
        return $this->morphTo('subject');
    }
}

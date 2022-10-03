<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'name',
        'details',
        'description',
        'image',
        'images',
        'video',
        'video_thumbnail',
        'user_id',
        'contest_id',
        'status',
        'amount',
        'position',
        'slug'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function contest()
    {
        return $this->belongsTo('App\Models\Contest');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\Vote');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}

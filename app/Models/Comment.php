<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    protected $fillable = [
        'comment',
        'user_id',
        'participant_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant');
    }
}

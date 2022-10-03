<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;

class Transaction extends Model
{
    protected $fillable = [
        'kitty',
        'amount',
        'method',
        'status',
        'type',
        'user_id',
        'transaction_token',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;

class Vote extends Model
{
    protected $fillable = [
        'user_id',
        'contest_id',
        'participant_id',
        'transaction_id',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant');
    }

    public function contest()
    {
        return $this->belongsTo('App\Models\Contest');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }
}

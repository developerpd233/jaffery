<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'title',
        'image',
        'status',
        'slug',
        'description',
        'category_id',
        'start_date',
        'end_date',
        'type_id',
        'amount'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\Participant');
    }


    public function votes()
    {
        return $this->hasMany('App\Models\Vote');
    }
}

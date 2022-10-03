<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;

class City extends Model
{
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}

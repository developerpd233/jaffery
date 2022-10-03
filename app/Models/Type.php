<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;

class Type extends Model
{
    public function contests()
    {
        return $this->hasMany('App\Models\Contest');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;

class Profession extends Model
{
	protected $fillable = ['name','slug','image'];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}

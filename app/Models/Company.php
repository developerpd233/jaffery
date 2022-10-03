<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;


class Company extends Model
{
	protected $fillable = [
        'key',
        'value',
    ];
}

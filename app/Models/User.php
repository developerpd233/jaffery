<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Socialite;
use Auth;
use App\Models\Favourite;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'display_name',
        'email',
        'password',
        'username',
        'avatar',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'trophies',
        'profesion_id',
        'date_of_birth',
        'bio',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'provider', 
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\Participant');
    }

    public function favourites()
    {
        $favourites = Favourite::where('user_id',auth()->user()->id)->pluck('participant_id')->toArray();

        $participants = Participant::whereIn('id', $favourites)->get(); 

        return $participants;
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}

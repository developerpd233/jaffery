<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\Models\User;

class SocialController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/contests';

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        //dd($userSocial);
        $user = User::where(['email' => $userSocial->getEmail()])->first();
        
        if($user){
            Auth::login($user);
            return redirect('/');
        }
        else
        {
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'avatar'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
                'role_id' => 2
            ]);
            
            event(new Registered($user));

            $this->guard()->login($user);

            // if ($response = $this->registered($request, $user)) {
            //     return $response;
            // }

            return redirect($this->redirectPath());
        }
    }
}
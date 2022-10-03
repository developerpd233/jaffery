<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/contests';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'image' => ['nullable', 'image'],
            'address' => ['nullable', 'string', 'max:255'],
            'country_id' => ['required'],
            'state_id' => ['nullable'],
            'city_id' => ['nullable'],
            'date_of_birth' => ['required', 'date'],
            'bio' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'profession' => ['required'],
            //'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ]);
    }

    public function register(Request $request)
    {
        $response = Storage::makeDirectory('/public/users/'.date('FY'));

        $this->validator($request->all())->validate();   

        $filepath = '';
        
        //dd($request->all());

        if ($request->has('image')) {
            
            $rand = md5(microtime());
            $image       = $request->file('image');
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath = '/users/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save('storage/app/public/users/'.date('FY').'/'.$filename);
        }
        
        $data = $request->all();

        if ($request->other_profession != '' || $request->profession == '351') 
        {
            $professions = Profession::where('name',$request->other_profession)->count();

            if ($professions == 0) 
            {
                $profession = Profession::create([
                    'name' => $request->other_profession,
                    'slug' => Str::slug(str_replace('/', ' ', $request->other_profession), '-'),
                    'image' => '',
                ]);

                $data['profession'] = $profession->id;
            }
        }

        // dd($data);

        $user = User::create([
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'username' => $data['username'],
            'avatar' => $filepath,
            'address' => $data['address'],
            'country_id' => $data['country_id'],
            'state_id' => isset($data['state_id']) ? $data['state_id'] : 0,
            'city_id' => isset($data['city_id']) ? $data['city_id'] : 0,
            'trophies' => 0,
            'date_of_birth' => $data['date_of_birth'],
            'bio' =>  isset($data['bio']) ? $data['bio'] : '',
            'facebook' => isset($data['facebook']) ? $data['facebook'] : '',
            'twitter' => isset($data['twitter']) ? $data['twitter'] : '',
            'linkedin' => isset($data['linkedin']) ? $data['linkedin'] : '',
            'instagram' => isset($data['instagram']) ? $data['instagram'] : '',
            'profession_id' => $data['profession'],
        ]);
      
        event(new Registered($user));

        try 
        {
            Mail::to($request->email)->send(new WelcomeMail($user));    
        } 
        catch (\Throwable $th) {
           
        }

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data,Request $request)
    {
        // dd($data,$request);
        // $filepath = '';

        // if ($request->has('image')) {
        //     $image       = $request->file('image');
        //     dd($image->getClientOriginalName());
        // } else {
        //     dd('f');
        // }
        
        // if (isset($data['avatar'])) {
        //     $image       = $request->file('image');
        //     $filename    = $image->getClientOriginalName();

        //     //Fullsize
        //     $image->move(public_path().'/full/',$filename);

        //     $image_resize = Image::make(public_path().'/full/'.$filename);
        //     $image_resize->fit(300, 300);
        //     $image_resize->save(public_path('thumbnail/' .$filename));
        // }
        
        // $user = User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        //     'username' => $data['username'],
        //     'avatar' => $filepath,
        //     'address' => $data['address'],
        //     'country_id' => $data['country_id'],
        //     'state_id' => $data['state_id'],
        //     'city_id' => $data['city_id'],
        //     'trophies' => 0,
        // ]);

        // return $user;
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
    }
}
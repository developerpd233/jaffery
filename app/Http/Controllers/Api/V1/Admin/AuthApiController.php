<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;
use App\Models\Profession;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Auth\Events\Registered;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class AuthApiController extends Controller
{
    public function checkEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string||max:100|unique:users,email'
        ]);

        $res = [
            'status' => 'success'
        ];
        return response($res, 201);
    }
    
    public function signup(Request $request)
    {
        $data = $request->validate([
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
        ]);

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

        $filepath = '';

        if ($request->has('image')) {
            
            $rand = md5(microtime());
            $image       = $request->file('image');
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath = '/users/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save(storage_path().'/app/public/users/'.date('FY').'/'.$filename);
        }

        $data['password'] = bcrypt($data['password']);
        $data['trophies'] = 0;
        $data['avatar'] = $filepath;
        $data['profession_id'] = $data['profession'];

        $user = User::create($data);

        event(new Registered($user));

        try 
        {
            Mail::to($request->email)->send(new WelcomeMail($user));    
        } 
        catch (\Throwable $th) {
           
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response($res, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'Incorrect email or password.'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }

    public function socialLogin(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string||max:100',
            'provider' => 'required|string|in:google,apple',
            'provider_id' => 'required|string|max:255'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'provider' => $data['provider'],
                'provider_id' => $data['provider_id'],
                'role_id' => 2
            ]);

            $user = User::where('email', $data['email'])->first();
        }
        else{
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'provider' => $data['provider'],
                'provider_id' => $data['provider_id'],
            ]);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];
    }

    public function forgotPassword(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $res = ["error" => "We can't find a user with that e-mail address."];
            return response($res, 401);         
        }else{
            $user->update([
                'forgot_otp' => rand(0, 999999),
                'forgot_expiry_time' => date(now())
            ]);

            try 
            {
                Mail::to($request->email)->send(new \App\Mail\forgot_otp($user));

                $res = ['status' => 'success', 'message' => 'OTP sent to your email address.'];
                return response($res, 201);
            } 
            catch (\Throwable $th) 
            {
                $res = ['status' => 'failed', 'error' => $th];
                return response($res, 401);
            }
        } 
    }

    public function forgotPasswordOTPCheck(Request $request){

        $request->validate([
            'otp_number' => 'required|integer'
        ]);

        $user = User::where('forgot_otp',$request->otp_number)->first();

        if ($user) 
        {
            $current_date=Carbon::parse(date(now()));
            $forgot_expiry_time=Carbon::parse($user->forgot_expiry_time);
            $date_diff = $current_date->diffInMinutes($forgot_expiry_time);
            
            if ($date_diff <= 5) {

                $token = $user->createToken('apiToken')->plainTextToken;

                $user->update([
                    'forgot_otp' => NULL,
                    'forgot_expiry_time' => NULL,
                    'otp_token' => $token
                ]);

                $res = ['status' => 'success', 'message' => 'OTP verification successful', 'token' => $token];
                return response($res, 201);
            }
            else
            {
                $res = ['status' => 'failed', 'message' => 'OTP verification timeout, please send code again'];
            }
        } 
        else 
        {
            $res = ['status' => 'failed', 'message' => 'OTP Number not matched, please try again'];
        }
        
        if ($res['status'] == 'success') {
            return response($res, 201);
        }
        
        return response($res, 401);
    }


    public function forgotPasswordreset(Request $request){

        $request->validate([
            'token' => 'required|string',
            'password' => 'required||min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8'
        ]);

        $user = User::where('otp_token',$request->token)->first();

        if ($user) 
        {
            $user->update([
                'password' => Hash::make($request->password),
                'otp_token' => NULL
            ]);

            $res = ['status' => 'success', 'message' => 'Change password is successful'];
            return response($res, 201);
        } 
        else 
        {
            $res_failed = ['status' => 'failed', 'message' => 'Token not matched'];
            return response($res_failed, 404);
        }
    }
}
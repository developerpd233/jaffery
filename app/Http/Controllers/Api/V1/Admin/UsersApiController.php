<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersApiController extends Controller
{
    public function showProfile()
    {
        $res = [
            'data' => auth()->user()
        ];
        return response($res, 200);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,id,'.$user->id],
            'password' => ['required', 'string', 'min:8'],
            'username' => ['required', 'string', 'max:255', 'unique:users,id,'.$user->id],
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
           
        $data = $request->all();
        $data['profession_id'] = $data['profession'];

        //dd($data);

        $user->update($data);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function updateProfileImage(Request $request)
    {
        $user = auth()->user();
        
        $data = $request->validate([
            'image' => 'required|image'
        ]);

        $unique = bin2hex(random_bytes(10));
        $file_pre_path = $user->image;
        $file_pre = storage_path().'/app/public'.$user->image;
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension(); // you can also use file name
        $fileName = $unique.'.'.$extension;
        $path = storage_path().'/app/public/users/'.date('FY');
        $uplaod = $file->move($path,$fileName);

        $user->image = '/users/'.date('FY').'/'.$fileName;
        $user->avatar = '/users/'.date('FY').'/'.$fileName;

        if ($user->save()) 
        {
            if ($file_pre_path != null && $file_pre_path != '' && file_exists($file_pre)) {
            //if (file_exists($file_pre)) {
                unlink($file_pre);
            }
            
            $res = [
                'status' => 'success',
                'message' => 'Profile image updated.',
                'user' => $user
            ];
        } 
        else {
            $res = [
                'status' => 'error',
                'message' => 'Something went wrong!'
            ];
        }
        
        return response($res, 201);
    }

    public function show(User $user)
    {
        $user = User::with(['country','state','city','profession','participants'])->where('id',$user->id)->where('status','1')->first();

        $latest_participant = $user->participants()->with('votes')->latest()->first();
        $participants = $user->participants;
        $ongoing_contests = collect();
        $past_contests = collect();
        $now = now()->format('Y-m-d');

        foreach ($participants as $key => $participant) {
           
            $start = date('Y-m-d',strtotime($participant->contest->start_date));
            $end = date('Y-m-d',strtotime($participant->contest->end_date));

            if ($start <= $now && $now <= $end) {
                $ongoing_contests->push($participant->contest);
            } 
            else if ($now > $end) {
                $past_contests->push($participant->contest);
            }
        }

        $res['data'] = [
            'user' => $user,
            'latest_participant' => $latest_participant,
            'ongoing_contests' => $ongoing_contests,
            'past_contests' => $past_contests
        ];
                
        return response($res, 201);
        // return new UserResource($user->load(['roles']));
    }
}

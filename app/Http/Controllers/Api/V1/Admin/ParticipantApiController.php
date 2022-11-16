<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Vote;
use App\Models\Company;
use App\Models\Participant;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ParticipantApiController extends Controller
{
    public function uploadImage(Request $request) {

        // if (participantExist($request->contest_id)) {
        //     return back()->with('error' , 'You already participated in this contest!');
        // }

        $response = Storage::makeDirectory('/public/participants/'.date('FY'));

        $this->validate($request, [
            'contest_id' => 'required|exists:contests,id',
            'name' => 'required|max:100',
            'detail' => 'required|max:2000',
            'description' => 'required|max:2000',
            'image' => 'required|image',
            'images' => 'nullable|array|min:1|max:5',
            'images.*' => 'required|image',
        ]);  

        $filepath = '';
        $filepaths = '';
        $filepath_video = '';
        $filepath_video_thumbnail = '';
        $filePathArr = [];

        if ($request->has('image')) {
            $rand = md5(microtime());
            $image       = $request->file('image');
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath = '/participants/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save(storage_path().'/app/public/participants/'.date('FY').'/'.$filename);
        }
        
        $files = $request->file('images');

        if($request->hasFile('images'))
        {
            foreach ($files as $key => $file) {
                $rand = md5(microtime());
                $image       = $file;
                $filename    = $rand.'.'.$image->getClientOriginalExtension();
                $filepaths = '/participants/'.date('FY').'/'.$filename;      
                $image_resize = Image::make($image);
                $image_resize->save(storage_path().'/app/public/participants/'.date('FY').'/'.$filename);
                array_push( $filePathArr , $filepaths );
            }
        }

        $data = $request->all();

        $new_participant = Participant::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $data['contest_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'details' => $data['detail'],
            'image' => $filepath,
            'images' => json_encode($filePathArr),
            'video' => $filepath_video,
            'video_thumbnail' => $filepath_video_thumbnail,
        ]);


        if ($new_participant) {

            $vote = Vote::create([
                'user_id' => auth()->user()->id,
                'contest_id' => $data['contest_id'],
                'participant_id' => $new_participant->id,
                'amount' => 0
            ]);

            $feature_ids = DB::table('votes')
                             ->select(DB::raw('count(*) as vote_count, participant_id'))
                             ->where('contest_id', '=', $data['contest_id'])
                             ->groupBy('participant_id')
                             ->orderByDesc('vote_count')
                             ->pluck('participant_id')
                             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::whereIn('id', $feature_ids)
                        ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                        ->get();

            foreach ($participants as $key => $participant) {
                $participant->position = $key + 1;
                $participant->save();
            }

            $res = ['status' => 'success', 'message' => 'You are participated successfully.', 'data' => $new_participant];
            return response($res, 200);
        }

        $res = ['status' => 'error', 'message' => 'Please try again. Something went wrong!'];
        return response($res, 400);
    }

    public function uploadVideo(Request $request) {

        // if (participantExist($request->contest_id)) {
        //     return back()->with('error' , 'You already participated in this contest!');
        // }

        $response = Storage::makeDirectory('/public/participants/'.date('FY'));

        $this->validate($request, [
            'contest_id' => 'required|exists:contests,id',
            'name' => 'required|max:100',
            'detail' => 'required|max:2000',
            'description' => 'required|max:2000',
            'image' => 'required|image',
            'video' => 'required|mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm|max:20000',
            'video_thumbnail' => 'required|image',
        ]);  

        $filepath = '';
        $filepath_video = '';
        $filepath_video_thumbnail = '';
        $filePathArr = [];

        if ($request->has('image')) {
            $rand = md5(microtime());
            $image       = $request->file('image');
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath = '/participants/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save(storage_path().'/app/public/participants/'.date('FY').'/'.$filename);
        }

        if ($request->has('video_thumbnail')) {
            $rand = md5(microtime());
            $image       = $request->file('video_thumbnail');
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath_video_thumbnail = '/participants/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save(storage_path().'/app/public/participants/'.date('FY').'/'.$filename);
        }

        if ($request->has('video')) {
            $rand = md5(microtime());
            $video      = $request->file('video');
            $filename_video    = $rand.'.'.$video->getClientOriginalExtension();
            $filepath_video = '/participants/'.date('FY').'/'.$filename_video;      
            
            $path = storage_path().'/app/public/participants/'.date('FY').'/';
            $video->move($path, $filename_video);
        }

        $data = $request->all();

        $new_participant = Participant::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $data['contest_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'details' => $data['detail'],
            'image' => $filepath,
            'images' => json_encode($filePathArr),
            'video' => $filepath_video,
            'video_thumbnail' => $filepath_video_thumbnail,
        ]);


        if ($new_participant) {

            $vote = Vote::create([
                'user_id' => auth()->user()->id,
                'contest_id' => $data['contest_id'],
                'participant_id' => $new_participant->id,
                'amount' => 0
            ]);

            $feature_ids = DB::table('votes')
                             ->select(DB::raw('count(*) as vote_count, participant_id'))
                             ->where('contest_id', '=', $data['contest_id'])
                             ->groupBy('participant_id')
                             ->orderByDesc('vote_count')
                             ->pluck('participant_id')
                             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::whereIn('id', $feature_ids)
                        ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                        ->get();

            foreach ($participants as $key => $participant) {
                $participant->position = $key + 1;
                $participant->save();
            }

            $res = ['status' => 'success', 'message' => 'You are participated successfully.', 'data' => $new_participant];
            return response($res, 200);
        }

        $res = ['status' => 'error', 'message' => 'Please try again. Something went wrong!'];
        return response($res, 400);
    }

    public function show(Request $request, $id) {

        $participant = Participant::with('user', 'contest')->where('id',$id)->first();

        if (!$participant) {
            return response(['message' => 'Data not found!'], 404);
        }

        $user = $participant->user;
        // $comments = $participant->comments()->with('user')->latest()->take(20)->get();
        $votes = $participant->votes()->with('user')->latest()->take(10)->get();

        if(favouriteExist($participant->id))
        {
            $participant->favourite = true;
        }
        else{
            $participant->favourite = false;
        }

        $participant->country = $user->country->name;
        $participant->state = isset($user->state) ? $user->state->name : '';
        $participant->city = isset($user->city) ? $user->city->name : '';
        // $participant->comments = $comments;
        $participant->votes = $votes;

        $res = ['participant' => $participant];
        return response($res, 200);
    }

    public function vote(Request $request)
    {
        $this->validate($request, [
            'participant_id' => 'required|exists:participants,id',
            'amount' => 'required|numeric',
            'order_id' => 'required|string',
            'method' => 'required|string',
            'message' => 'required|string',
        ]); 

        $data = $request->all();

        // dd($data);

        $participant = Participant::findOrFail($data['participant_id']);
        $company = Company::where('key','amount')->first();
        $contest = $participant->contest;
        $user = auth()->user();
        $amount = $data['amount'];

        //dd($contest);

        if ($contest->type->slug == 'monthly') {
            $kitty = ( $amount / 100 ) * 57;
            $contest_amount = $amount - $kitty;
        }
        elseif ($contest->type->slug == 'video') {
            $kitty = ( $amount / 100 ) * 57;
            $contest_amount = $amount - $kitty;
        } 
        else {
            $count = Vote::where('contest_id',$contest->id)->where('user_id', $user->id)->count();

            if ($count == 0) {
                $kitty = $amount;
                $contest_amount = 0;
            } else {
                $kitty = 0;
                $contest_amount = $amount;
            }
        }        

        $company_amount = $kitty;

        $contest->amount = $contest->amount + $contest_amount;
        $contest->save();

        if ($company) {
            $company->value = $company->value + $company_amount;
            $company->save();
        }else{
            $company = Company::create([
                'key' => 'amount',
                'value' => $company_amount,
            ]);
        }

        
        $vote = Vote::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $contest->id,
            'participant_id' => $participant->id,
        ]);

        if ($vote) {

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'status' => $request->message,
                'method' => $request->method,
                'amount' => intval($data['amount']),
                'kitty' => $kitty,
                'type' => 'vote',
                'transaction_token' => $request->order_id
            ]);

            try 
            {
                
                // Mail::to('theoflas@yahoo.com')->send(new TransactionMail($transaction,auth()->user(),$participant,$participant->contest));

                Mail::to(auth()->user()->email)->send(new TransactionMail($transaction,auth()->user(),$participant,$participant->contest));
                
            }
            catch (\Throwable $th) {
               
            }

            $vote->transaction_id = $transaction->id;
            $vote->save();

            $feature_ids = DB::table('votes')
                             ->select(DB::raw('count(*) as vote_count, participant_id'))
                             ->where('contest_id', '=', $contest->id)
                             ->groupBy('participant_id')
                             ->orderByDesc('vote_count')
                             ->pluck('participant_id')
                             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::whereIn('id', $feature_ids)
                        ->where('status', 1)
                        ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                        ->get();

            $contest_amount = $contest->amount;
            $first_prize = $contest_amount;

            foreach ($participants as $key => $participant) {
                
                if ($key == 0) {
                    $participant->amount = $first_prize;
                }
                else{
                    $participant->amount = 0;   
                }
                
                $participant->position = $key + 1;
                $participant->save();
            }

            return response()->json(['code'=>200, 'message'=>'Vote submitted successfully','url' => route('participant.show',$participant->id)], 200);
            //return redirect(route('participant.show',$info[0]['participant_id']))->with('success' , 'You are voted successfully.');
        }
        
        return response()->json(['code'=>404, 'message'=>'Something went wrong in voting!'], 200);
        //return redirect(route('participant.show',$participant->id))->with('error' , 'Something went wrong in voting!');
    }
}

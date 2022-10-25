<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;

use App\Models\Vote;
use App\Models\Transaction;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    public function show(Request $request, Participant $participant) {

        $comments = $participant->comments()->latest()->take(20)->get();
        $votes = $participant->votes()->latest()->take(10)->get();

        $participant->load('user', 'contest');

        $participant->comments = $comments;
        $participant->votes = $votes;

        $res = ['participant' => $participant];
        return response($res, 200);
    }
}

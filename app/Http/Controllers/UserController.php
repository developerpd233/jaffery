<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favourite;
use App\Models\Participant;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('favourites');
    }

    public function show($id)
    {
        // $user = User::findOrFail($id);
        $user = User::where('id',$id)->where('status','1')->first();

        if (!$user) {
            abort(404);
        }

        $latest_participant = $user->participants()->latest()->first();
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

        return view('single-user', compact('user','latest_participant','participants','ongoing_contests','past_contests'));
    }

    public function favouriteCreate(Request $request)
    {
        $participant = Participant::findOrFail($request->participant_id);

        $favourite = Favourite::create([
            'participant_id' => $participant->id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json(['code'=>200, 'message'=>'Favourite Created successfully','data' => $favourite], 200);
    }

    public function favouriteDelete(Request $request)
    {
        $participant = Participant::findOrFail($request->participant_id);

        $favourite = Favourite::where('user_id',auth()->user()->id)->where('participant_id',$participant->id)->first();

        $favourite->delete();

        return response()->json(['code'=>200, 'message'=>'Favourite Deleted successfully','data' => $favourite], 200);
    }

    public function favourites()
    {
        $user = auth()->user();
        $favourites = $user->favourites();

        return view('participants.favourites', compact('user','favourites'));
    }
}
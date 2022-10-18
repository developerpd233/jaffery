<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\City;
use App\Models\State;
use App\Models\Contest;
use App\Models\Country;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Profession;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ListApiController extends Controller
{
    public function listParticipants(Request $request) {

        $participants = collect();
        $text = '';
        $type = '';

        if ( isset($request->keyword) && isset($request->type) ) {
        
            $text = $request->keyword;
            $type = $request->type;

            if ($type == 'country') {
                $type = Country::where('name', $text)->first();
            }
            else if ($type == 'state') {
                $type = State::where('name', $text)->first();
            }
            else if ($type == 'city') {
                $type = City::where('name', $text)->first();
            }
            else if ($type == 'profession') {
                $type = Profession::where('name', $text)->first();
            }else{
                $type = null;
            }

            if ($type) {
                $user_ids = $type->users()->pluck('id')->toArray();
        
                $participants = Participant::withCount('votes')
                    ->whereIn('user_id', $user_ids)
                    ->where('status', '=', '1')
                    ->orderByDesc('votes_count')
                    ->get();
            }
        }

        $res['data'] = $participants;
        return response($res, 201);
    }

    public function search(Request $request) {

        $results = collect();
        $text = '';
        $type = '';
        
        if ( isset($request->keyword) && isset($request->type) ) {
            $text = $request->input('keyword');
            $type = $request->input('type');
        
            if ($type == 'country') {
                $results = Country::where('name', 'Like', '%'.$text.'%')->get();
            }
            else if ($type == 'state') {
                $results = State::where('name', 'Like', '%'.$text.'%')->get();
            }
            else if ($type == 'city') {
                $results = City::where('name', 'Like', '%'.$text.'%')->get();
            }
            else if ($type == 'profession') {
                $results = Profession::where('name', 'Like', '%'.$text.'%')->get();
            }
        }

        $res['data'] = $results;
        return response($res, 201);
    }

    public function contestants()
    {
        $contests = Contest::where('status', 1)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->get();
        
        foreach ($contests as $key => $contest) {
            $participants = Participant::withCount('votes')
                ->with('user')
                ->where('contest_id', $contest->id)
                ->where('status', 1)
                ->orderByDesc('votes_count')
                ->take(5)
                ->get();

            $contest->participants = $participants;
        }

        $res['data'] = $contests;
        return response($res, 201);
    }

    public function favourites()
    {
        $user = auth()->user();
        $favourites = $user->favourites()->pluck('id');

        $favourites = Participant::withCount('votes')
                ->whereIn('id', $favourites)
                ->with('user')
                ->get();

        $res['data'] = $favourites;
        return response($res, 201);
    }

    public function addFavourite(Request $request)
    {
        $participant = Participant::findOrFail($request->participant_id);

        $favourite = Favourite::create([
            'participant_id' => $participant->id,
            'user_id' => auth()->user()->id
        ]);

        if ($favourite) {
            $res['data'] = [
                'status' => 'success',
                'message' => 'Add to favourites',
                'favourite' => $favourite
            ];
        }
        else {
            $res['data'] = [
                'status' => 'failed',
                'message' => 'Request failed. Pllease try again!',
            ];
        }

        return response($res, 201);
    }

    public function removeFavourite(Request $request)
    {
        $favourite = Favourite::where('user_id', auth()->user()->id)->where('participant_id', $request->participant_id)->delete();

        if ($favourite) {
            $res['data'] = [
                'status' => 'success',
                'message' => 'Remove from favourites'
            ];
        }
        else {
            $res['data'] = [
                'status' => 'failed',
                'message' => 'Request failed. Please try again!',
            ];
        }

        return response($res, 201);
    }

    public function winners()
    {
        $participants = collect();
        $contests = Contest::where('status', 1)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->get();

        foreach ($contests as $key => $contest) {

            $winners = Participant::withCount('votes')
                ->with('user')
                ->where('contest_id', $contest->id)
                ->where('status', 2)
                ->orderByDesc('votes_count')
                ->get();

            foreach ($winners as $key => $winner) {
                $participants->push($winner);
            }

            // $winner = Participant::withCount('votes')
            //     ->with('user')
            //     ->where('contest_id', $contest->id)
            //     ->where('status', 2)
            //     ->orderByDesc('votes_count')
            //     ->take(1)
            //     ->first();

            // if ($winner) {
            //     $participants->push($winner);
            // }
        }

        $res['data'] = $participants;
        return response($res, 201);
    }
}

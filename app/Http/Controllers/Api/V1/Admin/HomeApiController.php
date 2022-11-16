<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\User;
use App\Models\Vote;
use App\Models\Contest;
use App\Models\Category;
use App\Models\Participant;
use App\Models\Profession;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class HomeApiController extends Controller
{
    public function search(Request $request) {

        $text = $request->input('search');
        $type = $request->input('type');
        $contest_id = $request->input('contest_id');
        $results = collect();

        if ($type == 'participant') {
            $results = Participant::where('contest_id', $contest_id)->where('name', 'Like', '%'.$text.'%')->where('status', '=', '1')->get();
        }
        else if ($type == 'country') {
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
        else {
            // search the members table
            $users = User::where('name', 'Like', '%'.$text.'%')->get();
            $participants = Participant::where('name', 'Like', '%'.$text.'%')->where('status', '=', '1')->get();
            
            if ($users->count() > $participants->count()) {
                foreach ($users as $key => $val) {
                    $results->push($val);
                    // just in case the 2 arrays are not the same length
                    if ( isset($participants[$key]) ){
                        $results->push($participants[$key]);
                    }
                }
            } else {
                foreach ($participants as $key => $val) {
                    $results->push($val);
                    // just in case the 2 arrays are not the same length
                    if ( isset($users[$key]) ){
                        $results->push($users[$key]);
                    }
                }
            }
        }
        
        $res['data'] = $results;
                
        return response($res, 201);
    }

    public function contests() {

        $annual_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'annual');
            })
            // ->where('start_date','<=',now()->format('Y-m-d'))
            // ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $video_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'video');
            })
            // ->where('start_date','<=',now()->format('Y-m-d'))
            // ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $monthly_contests = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            // ->where('start_date','<=',now()->format('Y-m-d'))
            // ->where('end_date','>=',now()->format('Y-m-d'))
            ->take(6)
            ->get();
        
        $res['data'] = [
            'video_contest' => $video_contest,
            'annual_contest' => $annual_contest,
            'monthly_contests' => $monthly_contests,
        ];
                
        return response($res, 201);
    }
}

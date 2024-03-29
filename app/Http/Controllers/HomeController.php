<?php

namespace App\Http\Controllers;

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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function landing()
    {

        //dd(now()->format('Y-m-d'));

        $contests = Contest::where('status', 1)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->get();

        $annual_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'annual');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $monthly_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $video_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'video');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $participants = collect();


        if ($monthly_contest) {

            if ($monthly_contest->participants()->count() > 4) {
                $participants = $monthly_contest->participants()->where('status', '=', '1')->latest()->take(5)->get();
            }
            else {
                if ($contests->count() > 0) {

                    foreach ($contests as $key => $contest) {
                        if ($contest->participants()->count() > 4)
                        {
                            $participants = $contest->participants()->where('status', '=', '1')->latest()->take(5)->get();
                        }
                    }
                }
            }

            if ($participants->count() < 5) {
                if ($contests->count() > 0) {

                    foreach ($contests as $key => $contest) {
                        if ($contest->participants()->count() > 4)
                        {
                            $participants = $contest->participants()->where('status', '=', '1')->orWhere('status', '=', '2')->latest()->take(5)->get();
                        }
                    }
                }
            }
        }
        $video_votes= collect([]);
        $video_participants= collect([]);
        if ($video_contest && $video_contest->participants->count() > 0)
        {
            $video_participants = $video_contest->participants()->where('status', '=', '1')->latest()->take(10)->get();
            $video_votes = $video_contest->votes()->latest()->take(5)->get();

            if ($video_participants->count() == 0) {
                $video_participants = $video_contest->participants()->where('status', '=', '2')->latest()->take(10)->get();
            }
        }

        $mon_contests = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->take(6)
            ->get();

        return view('welcome', compact('video_contest','monthly_contest','annual_contest','video_votes','video_participants','participants','contests','mon_contests'));
    }

    public function search(Request $request) {
        // get the search term
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

        // return the results
        return response()->json($results);
    }

    public function monthlyContest(Request $request) {

        $contests = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->take(6)
            ->get();

        return response()->json($contests);
    }

    public function annualParticipants(Request $request) {

        $annual_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'annual');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();


        $participants = collect();

        if ($annual_contest)
        {
            $participants = $annual_contest->participants()->where('status','1')->get();

            if ($participants->count() == 0) {
                $participants = $annual_contest->participants()->where('status', '2')->get();
            }
        }

        return response()->json($participants);
    }

    public function videoParticipants(Request $request) {

        $video_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'video');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $participants = collect();

        if ($video_contest)
        {
            $participants = $video_contest->participants()->where('status','1')->get();

            if ($participants->count() == 0) {
                $participants = $video_contest->participants()->where('status', '2')->get();
            }
        }

        return response()->json($participants);
    }

    public function monthlyParticipants(Request $request) {

        $contests = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->take(6)
            ->get();

        $monthly_participants = collect();

        foreach ($contests as $key => $contest) {

            $participants = $contest->participants()->where('status','1')->get();

            foreach ($participants as $key => $participant)
            {
                $monthly_participants->push($participant);
            }
        }

        if ($monthly_participants->count() == 0) {

            foreach ($contests as $key => $contest) {

                $participants = $contest->participants()->where('status','2')->get();

                foreach ($participants as $key => $participant)
                {
                    $monthly_participants->push($participant);
                }
            }
        }

        return response()->json($monthly_participants);
    }
}

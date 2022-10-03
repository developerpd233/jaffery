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

        //dd($monthly_contest->participants->count());
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
                            // dd($participants);
                        }
                    }
                }
            }
            
            
            // $participants = $monthly_contest->participants()->latest()->take(5)->get();  
            //$votes = $monthly_contest->votes()->latest()->take(5)->get();    
            // $feature_ids = DB::table('votes')
            //      ->select(DB::raw('count(*) as vote_count, participant_id'))
            //      ->where('contest_id', '=', $monthly_contest->id)
            //      ->groupBy('participant_id')
            //      ->orderByDesc('vote_count')
            //      ->pluck('participant_id')
            //      ->take(5)->toArray();

            // if (count($feature_ids) > 0) {
            //     $tempStr = implode(',', $feature_ids);
            //     $features = DB::table('participants')
            //     ->whereIn('id', $feature_ids)
            //     ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
            //     ->take(3)
            //     ->get();
            // } else {
            //     $features = collect();
            // }
        } 
        else 
        {
            //$participants = collect();  
            //$votes = collect();    
            //$features = collect();    
        }

        if ($video_contest && $video_contest->participants->count() > 0) {
            
            $video_participants = $video_contest->participants()->where('status', '=', '1')->latest()->take(10)->get();  
            $video_votes = $video_contest->votes()->latest()->take(5)->get();    
            // $video_feature_ids = DB::table('votes')
            //      ->select(DB::raw('count(*) as vote_count, participant_id'))
            //      ->where('contest_id', '=', $video_contest->id)
            //      ->groupBy('participant_id')
            //      ->orderByDesc('vote_count')
            //      ->pluck('participant_id')
            //      ->take(5)->toArray();

            // if (count($video_feature_ids) > 0) {
            //     $video_tempStr = implode(',', $video_feature_ids);
            //     $video_features = DB::table('participants')
            //     ->whereIn('id', $video_feature_ids)
            //     ->orderByRaw(DB::raw("FIELD(id, $video_tempStr)"))
            //     ->take(3)
            //     ->get();
            // } else {
            //     $video_features = collect();
            // }
        } 
        else 
        {
            $video_participants = collect();  
            $video_votes = collect();    
            $video_features = collect();    
        }
        
        $mon_contests = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->take(6)
            ->get();

        //$features = Participant::whereIn('id', $feature_ids)->get();
        //dd($features);
        
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
        
        //dd($monthly_participants);
        
        return response()->json($monthly_participants);
    }
}

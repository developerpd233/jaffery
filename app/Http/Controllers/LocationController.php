<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Profession;
use App\Models\Participant;
use App\Models\Vote;
use App\Models\Contest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class LocationController extends Controller
{
    public function countries($id)
    {  
        $country = Country::where('name',$id)->first();
        
        if (!$country) {
            abort(404);
        }

        $user_ids = $country->users()->pluck('id')->toArray();
        
        //$participant_ids = Participant::whereIn('user_id', $user_ids)->pluck('id')->toArray();
        
        // $votes = Vote::whereIn('participant_id', $participant_ids)->get();

        $monthly_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        if ($monthly_contest && $monthly_contest->participants->count() > 0) {
            $feature_ids = DB::table('votes')
             ->select(DB::raw('count(*) as vote_count, participant_id'))
            // This line comment then country show
            //   ->where('contest_id', '=', $monthly_contest->id)
            // This line comment then country show
             ->groupBy('participant_id')
             ->orderByDesc('vote_count')
             ->pluck('participant_id')
             ->toArray();
            

            // dd($feature_ids);
            
            $tempStr = implode(',', $feature_ids);
            $participants = Participant::
            whereIn('user_id', $user_ids)
            ->whereIn('id', $feature_ids)
            ->where('status', '=', '1')
            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
            ->get();
            // dd($participants);
            
        } 
        else 
        {
            $participants = collect();
            
        }

        //dd($participants);  

        return view('location.countries', compact('participants','country','monthly_contest'));
    }

    public function states($id)
    {  
        //$state = State::findOrFail($id);

        $state = State::where('name',$id)->first();
        
        if (!$state) {
            abort(404);
        }

        $user_ids = $state->users()->pluck('id')->toArray();

        $monthly_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        if ($monthly_contest && $monthly_contest->participants->count() > 0) {
            $feature_ids = DB::table('votes')
             ->select(DB::raw('count(*) as vote_count, participant_id'))
             // This line comment then country show
            //  ->where('contest_id', '=', $monthly_contest->id)
             // This line comment then country show
             ->groupBy('participant_id')
             ->orderByDesc('vote_count')
             ->pluck('participant_id')
             ->toArray();

            // dd($feature_ids);

            $tempStr = implode(',', $feature_ids);
            // dd($tempStr);

            $participants = Participant::
            whereIn('user_id', $user_ids)
            ->whereIn('id', $feature_ids)   
            ->where('status', '=', '1')
            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
            ->get();

            // dd($participants);
        } 
        else 
        {
            $participants = collect();
        }
        
        //dd($participants);  

        return view('location.states', compact('participants','state','monthly_contest'));
    }

    public function cities($id)
    {  
        //$city = City::findOrFail($id);

        $city = City::where('name',$id)->first();
        
        if (!$city) {
            abort(404);
        }

        $user_ids = $city->users()->pluck('id')->toArray();

        $monthly_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        if ($monthly_contest && $monthly_contest->participants->count() > 0) {
            $feature_ids = DB::table('votes')
             ->select(DB::raw('count(*) as vote_count, participant_id'))
             // This line comment then country show
            //  ->where('contest_id', '=', $monthly_contest->id)
             // This line comment then country show
             ->groupBy('participant_id')
             ->orderByDesc('vote_count')
             ->pluck('participant_id')
             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::
            whereIn('user_id', $user_ids)
            ->whereIn('id', $feature_ids)
            ->where('status', '=', '1')
            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
            ->get();
        } 
        else 
        {
            $participants = collect();
        }

        //dd($participants);  

        return view('location.cities', compact('participants','city','monthly_contest'));
    }

    public function professions($id)
    {  
        //$profession = Profession::findOrFail($id);

        $profession = Profession::where('name',$id)->first();
        
        if (!$profession) {
            abort(404);
        }

        $user_ids = $profession->users()->pluck('id')->toArray();

        $monthly_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        if ($monthly_contest && $monthly_contest->participants->count() > 0) {
            $feature_ids = DB::table('votes')
             ->select(DB::raw('count(*) as vote_count, participant_id'))
             // This line comment then country show
            //  ->where('contest_id', '=', $monthly_contest->id)
             // This line comment then country show
             ->groupBy('participant_id')
             ->orderByDesc('vote_count')
             ->pluck('participant_id')
             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::
            whereIn('user_id', $user_ids)
            ->whereIn('id', $feature_ids)
            ->where('status', '=', '1')
            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
            ->get();
        } 
        else 
        {
            $participants = collect();
        }

        //dd($participants);  

        return view('professions', compact('participants','profession','monthly_contest'));
    }

    public function getStates($id)
    {
        $states = State::where('country_id',$id)->get();
        return response($states,200);
    }

    public function getCities($id)
    {
        $cities = City::where('state_id',$id)->get();
        return response($cities,200);
    }
}

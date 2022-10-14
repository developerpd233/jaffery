<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Profession;
use App\Models\Participant;

class LocationController extends Controller
{
    public function countries($id)
    {  
        $country = Country::where('name',$id)->first();
        
        if (!$country) {
            return response([], 404);
        }

        $user_ids = $country->users()->pluck('id')->toArray();
        
        $participants = Participant::withCount('votes')
            ->whereIn('user_id', $user_ids)
            ->where('status', '=', '1')
            ->orderByDesc('votes_count')
            ->get(); 

        return view('location.countries', compact('participants','country'));
    }

    public function states($id)
    {  
        //$state = State::findOrFail($id);

        $state = State::where('name',$id)->first();
        
        if (!$state) {
            abort(404);
        }

        $user_ids = $state->users()->pluck('id')->toArray();

        $participants = Participant::withCount('votes')
            ->whereIn('user_id', $user_ids)
            ->where('status', '=', '1')
            ->orderByDesc('votes_count')
            ->get();  

        return view('location.states', compact('participants','state'));
    }

    public function cities($id)
    {  
        //$city = City::findOrFail($id);

        $city = City::where('name',$id)->first();
        
        if (!$city) {
            abort(404);
        }

        $user_ids = $city->users()->pluck('id')->toArray();

        $participants = Participant::withCount('votes')
            ->whereIn('user_id', $user_ids)
            ->where('status', '=', '1')
            ->orderByDesc('votes_count')
            ->get();

        return view('location.cities', compact('participants','city'));
    }

    public function professions($id)
    {  
        //$profession = Profession::findOrFail($id);

        $profession = Profession::where('name',$id)->first();
        
        if (!$profession) {
            abort(404);
        }

        $user_ids = $profession->users()->pluck('id')->toArray();

        $participants = Participant::withCount('votes')
            ->whereIn('user_id', $user_ids)
            ->where('status', '=', '1')
            ->orderByDesc('votes_count')
            ->get();  

        return view('professions', compact('participants','profession'));
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

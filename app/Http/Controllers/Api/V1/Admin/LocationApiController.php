<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Profession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationApiController extends Controller
{
    public function country(Request $request) {

        if (!empty($request->search)) 
        {
            $countries = Country::where('name','LIKE','%'.$request->search.'%')->get();
        } else {
            $countries = Country::get();
        }

        $res = ['data' => $countries];
        return response($res, 200);
    }

    public function state(Request $request, $id) {

        $country = Country::where('id',$id)->first();

        if (!empty($request->search)) 
        {
            $data = State::where('country_id',$country->id)->where('name','LIKE','%'.$request->search.'%')->get();
        } else {
            $data = State::where('country_id',$country->id)->get();
        }
        

        $res = ['data' => $data];
        return response($res, 200);
    }

    public function city(Request $request, $id) {

        $state = State::where('id',$id)->first();

        if (!empty($request->search)) 
        {
            $data = City::where('state_id',$state->id)->where('name','LIKE','%'.$request->search.'%')->get();
        } else {
            $data = City::where('state_id',$state->id)->get();
        }
        
        //$data = City::where('state_id',$state->id)->get();

        $res = ['data' => $data];
        return response($res, 200);
    }

    public function profession(Request $request) {

        if (!empty($request->search)) 
        {
            $data = Profession::where('name','LIKE','%'.$request->search.'%')->get();
        } else {
            $data = Profession::get();
        }

        $res = ['data' => $data];
        return response($res, 200);
    }

    // public function state(Request $request) {

    //     if (!empty($request->search)) 
    //     {
    //         $data = State::where('name','LIKE','%'.$request->search.'%')->get();
    //     } else {
    //         $data = State::get();
    //     }

    //     $res = ['data' => $data];
    //     return response($res, 200);
    // }

    // public function city(Request $request) {

    //     if (!empty($request->search)) 
    //     {
    //         $data = City::where('name','LIKE','%'.$request->search.'%')->get();
    //     } else {
    //         $data = City::get();
    //     }

    //     $res = ['data' => $data];
    //     return response($res, 200);
    // }
}

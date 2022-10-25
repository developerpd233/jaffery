<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Controllers\Controller;

class LocationApiController extends Controller
{
    public function country($id) {

        $country = Country::where('id',$id)->orWhere('name',$id)->first();

        if (!$country) {
            return response(['message' => 'Data not found!'], 404);
        }

        $res = ['data' => $country];
        return response($res, 200);
    }

    public function state($id) {

        $state = State::where('id',$id)->orWhere('name',$id)->first();

        if (!$state) {
            return response(['message' => 'Data not found!'], 404);
        }

        $res = ['data' => $state];
        return response($res, 200);
    }

    public function city($id) {

        $city = City::where('id',$id)->orWhere('name',$id)->first();

        if (!$city) {
            return response(['message' => 'Data not found!'], 404);
        }

        $res = ['data' => $city];
        return response($res, 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\Participant;

class ContestApiController extends Controller
{
    public function search(Request $request) {

        $keyword = '';
        $contests = collect();

        if ( isset($request->keyword) && !empty($request->keyword) ) {
            $keyword = $request->keyword;
        
            $contests = Contest::where('title', 'LIKE', '%'.$keyword.'%')
                ->where('status', 1)
                ->where('start_date','<=',now()->format('Y-m-d'))
                ->where('end_date','>=',now()->format('Y-m-d'))
                ->get();
        }
    
        $res['data'] = $contests;
                
        return response($res, 201);
    }

    public function index(Request $request,$type) {

        if ($type == 'monthly') 
        {
            $contests = Contest::where('status', 1)
                ->whereHas('type', function ($q) use ($type) {
                    $q->where('slug', $type);
                })
                ->where('start_date','<=',now()->format('Y-m-d'))
                ->where('end_date','>=',now()->format('Y-m-d'))
                ->take(6)
                ->get();
        } 
        else 
        {
            $contests = Contest::where('status', 1)
                ->whereHas('type', function ($q) use ($type) {
                    $q->where('slug', $type);
                })
                ->where('start_date','<=',now()->format('Y-m-d'))
                ->where('end_date','>=',now()->format('Y-m-d'))
                ->take(1)
                ->get();
        }
        
        $res['data'] = $contests;
                
        return response($res, 201);
    }

    public function show(Request $request, $id) {
        
        $contest = Contest::where('id',$id)->first();

        $participants = Participant::withCount('votes')
            ->with('user')
            ->where('contest_id', $contest->id)
            ->where('status', '=', '1')
            ->orderByDesc('votes_count')
            ->get();

        foreach ($participants as $key => $participant) 
        {    
            if(favouriteExist($participant->id))
            {
                $participant->favourite = true;
            }
            else{
                $participant->favourite = false;
            }
        }

        $res['data'] = $contest;
        $res['data']['participants'] = $participants;
                
        return response($res, 201);
    }
}

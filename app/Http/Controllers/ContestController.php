<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $contests = Contest::where('status',1)->orderByDesc('id')->paginate(10);
        // dd("acacas");
        $monthly_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->take(6)
            ->get();

            // dd($monthly_contest);

        $annual_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'annual');
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

        $contests = $monthly_contest;

        //dd($contests);
        return view('contests.index', compact('contests','annual_contest','video_contest'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contest = Contest::findOrFail($id);

        $feature_ids = DB::table('votes')
             ->select(DB::raw('count(*) as vote_count, participant_id'))
             ->where('contest_id', '=', $contest->id)
             ->groupBy('participant_id')
             ->orderByDesc('vote_count')
             ->pluck('participant_id')
             ->toArray();

        $tempStr = implode(',', $feature_ids);
        $participants = Participant::
        // whereIn('user_id', $user_ids)
        whereIn('id', $feature_ids)
        ->where('status', '=', '1')
        ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
        ->paginate(10);

        // $participants = Participant::
        // orderBy('position','asc')
        // ->paginate(10);

        //dd($participants);

        //dd($participants[0]->user);

        return view('contests.show', compact('contest','participants'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

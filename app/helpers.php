<?php

if(!function_exists('contest_amount')) {

	function contest_amount($id){
		if(!empty($id)){
			
			$contest = App\Models\Contest::find($id);

			$result = $contest->votes->sum('amount');
			$result = $contest->amount;
			//dd($result);
			return $result;
		}
	}
}

if(!function_exists('participantExist')) {

	function participantExist($contest_id){
		return App\Models\Participant::where('user_id',auth()->user()->id)->where('contest_id',$contest_id)->where('status', '=', '1')->exists();
	}
}

if(!function_exists('favouriteExist')) {

	function favouriteExist($participant_id){
		return App\Models\Favourite::where('user_id',auth()->user()->id)->where('participant_id',$participant_id)->exists();
	}
}

if(!function_exists('voteExist')) {

	function voteExist($contest_id , $participant_id){
		return App\Models\Vote::where('user_id',auth()->user()->id)->where('contest_id',$contest_id)->where('participant_id',$participant_id)->exists();
	}
}

if(!function_exists('featuredRecords')) {

	function featuredRecords($contest_id){

		$features = App\Models\Participant::withCount('votes')
            ->where('contest_id', $contest_id)
			->where('status', 1)
            ->orderByDesc('votes_count')
			->take(5)
            ->get();
        
		return $features;
	}
}

if(!function_exists('position')) {

	function position($num){
		
		$position = '';

		if($num == 0){
            $position = $num.' Positon';
		}
        elseif($num == 1){
            $position = $num.'st Positon';
        }
        elseif($num == 2){
            $position = $num.'nd Positon';
        }
        elseif($num == 3){
            $position = $num.'rd Positon';
        }
        else{
            $position = $num.'th Positon';
        }
        
		return $position;
	}
}

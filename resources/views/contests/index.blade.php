@extends('layouts.app')
@section('meta_title', 'Contests')
@section('meta_description', 'Contests')
@section('page_title', 'Contests')

@section('content')

<section class="contest_sec">
	<div class="container-fluid">
		
		@if($video_contest)
			<div class="box">
				<a class="img" href="{{ route('contest.show', $video_contest->id) }}"><img src="{{ Voyager::image($video_contest->image) }}"></a>
				<div class="txt">
					@php
						$start = strtoupper(date('M',strtotime($video_contest->start_date))).' '.date('d',strtotime($video_contest->start_date));
						$end = strtoupper(date('M',strtotime($video_contest->end_date))).' '.date('d',strtotime($video_contest->end_date));
					@endphp
					<h5>FROM {{ $start }} TO {{ $end }}</h5>
					<h2>{{ $video_contest->title }}</h2>
					<h4>To Win ${{ contest_amount($video_contest->id) }} USD, {{ $video_contest->participants()->count() }} Contestants</h4>
					<a href="{{ route('contest.show', $video_contest->id) }}">View The Contest</a>
				</div>
			</div>
		@endif

		@if($annual_contest)
			<div class="box">
				<a class="img" href="{{ route('contest.show', $annual_contest->id) }}"><img src="{{ Voyager::image($annual_contest->image) }}"></a>
				<div class="txt">
					@php
						$start = strtoupper(date('M',strtotime($annual_contest->start_date))).' '.date('d',strtotime($annual_contest->start_date));
						$end = strtoupper(date('M',strtotime($annual_contest->end_date))).' '.date('d',strtotime($annual_contest->end_date));
					@endphp
					<h5>FROM {{ $start }} TO {{ $end }}</h5>
					<h2>{{ $annual_contest->title }}</h2>
					<h4>To Win ${{ contest_amount($annual_contest->id) }} USD, {{ $annual_contest->participants()->count() }} Contestants</h4>
					<a href="{{ route('contest.show', $annual_contest->id) }}">View The Contest</a>
				</div>
			</div>
		@endif

		@forelse($contests as $contest)
			<div class="box">
				<a class="img" href="{{ route('contest.show', $contest->id) }}"><img src="{{ Voyager::image($contest->image) }}"></a>
				<div class="txt">
					@php
						$start = strtoupper(date('M',strtotime($contest->start_date))).' '.date('d',strtotime($contest->start_date));
						$end = strtoupper(date('M',strtotime($contest->end_date))).' '.date('d',strtotime($contest->end_date));
					@endphp
					<h5>FROM {{ $start }} TO {{ $end }}</h5>
					<h2>{{ $contest->title }}</h2>
					<h4>To Win ${{ contest_amount($contest->id) }} USD, {{ $contest->participants()->count() }} Contestants</h4>
					<a href="{{ route('contest.show', $contest->id) }}">View The Contest</a>
				</div>
			</div>
		@empty
		<h3>No contests yet</h3>
		@endforelse
		
		
	</div>
</section>

@endsection
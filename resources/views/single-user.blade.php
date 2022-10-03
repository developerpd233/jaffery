@extends('layouts.app')
@section('meta_title', 'User')
@section('meta_description', 'User')
@section('page_title', 'User')

@section('content')


<section class="user_p">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="main_user">
					<div class="usr-img">
						<div class="user_hd">

							<img src="{{ $user->provider ? $user->avatar : Voyager::image($user->avatar) }}">
						</div>
						<h2>{{ $user->name }}</h2>
					</div>
					
					<div class="usr_info">
						<h3>Trophies<span>{{ $user->trophies }}</span></h3>
						<h3><img src="{{ url('img/map159.svg') }}"><span>{{ isset($user->city->name) ? $user->city->name.', ' : '' }} {{ isset($user->state->name) ? $user->state->name : ''  }}</span></h3>
					</div>
					<div class="usr_tbs">
					  <!-- Nav tabs -->
					  <ul class="nav nav-tabs" role="tablist">
					    <li class="nav-item">
					      <a class="nav-link active" data-toggle="tab" href="#home">Profile</a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link" data-toggle="tab" href="#menu1">Contest</a>
					    </li>					    
					  </ul>

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div id="home" class="tab-pane active"><br>

					    	@if($latest_participant)
								<div class="full_img">
							      	<a href="#"><img src="{{ Voyager::image($latest_participant->image) }}"></a>
							      </div>
							      <h3>{{ $latest_participant->name }}</h3>
							      <h4>{{ isset($user->state->name) ? $user->state->name.' / ' : '' }} {{ isset($user->country->name) ? $user->country->name : ''  }}</h4>
							      	<div class="usr_full">
								    	<div class="main1">

								    		@php
								    			$images = json_decode($latest_participant->images, true);
								    		@endphp

								    		<h2>Photos</h2>
								    		@if(count($images) > 0)
												@foreach($images as $image)
													<div class="main_imgs">
										    			<a href="#"><img src="{{ Voyager::image($image) }}"></a>
										    		</div>
												@endforeach
											@else
												<h3>No images yet</h3>
											@endif
								    		
								    	</div>
								    	<div class="main2">
								    		<h2>Recent Voters</h2>
								    		
							    			@if(count($latest_participant->votes) > 0)
												@foreach($latest_participant->votes()->latest()->get() as $key => $voter)
													@if($key < 5)
														<div class="usr_vote">
															<div class="usr_v_img">
											    				<a href="{{ url('users/').'/'.$voter->user->id }}"><img src="{{ $voter->user->provider ? $voter->user->avatar : Voyager::image($voter->user->avatar) }}"></a>
											    			</div>
											    			<div class="usr_txt">
											    				<h5>{{ $voter->user->name }}</h5>
											    				<p>Vote Now</p>
											    			</div>
											    		</div>
													@endif
												@endforeach
											@else
												<h3>No images yet</h3>
											@endif
								    	</div>
							    	</div>
							@else
								<h4>Not participated any contest.</h4>
							@endif

					    </div>

					    <div id="menu1" class="container tab-pane fade"><br>
					      	<div class="secnd-main">
					      		<h2>On Going</h2>
					      		@if(count($ongoing_contests) > 0)
									@foreach($ongoing_contests as $key => $contest)
										<div class="going">
								      		<div class="scnd_tb_img">
								      			<a href="{{ url('contests/').'/'.$contest->id }}"><img src="{{ Voyager::image($contest->image) }}"></a>
								      		</div>
								      		<div class="scnd-tb_txt">
								      			<h3>{{ $contest->title }}</h3>
								      			<p>{{ $contest->participants()->count() }} Participants</p>
								      		</div>
								      	</div>
									@endforeach
								@else
									<h4>No contest yet</h4>
								@endif
					      	</div>

					      	<div class="secnd-main">
					      		<h2>Past</h2>
					      		@if(count($past_contests) > 0)
									@foreach($past_contests as $key => $contest)
										<div class="going">
								      		<div class="scnd_tb_img">
								      			<a href="{{ url('contests/').'/'.$contest->id }}"><img src="{{ Voyager::image($contest->image) }}"></a>
								      		</div>
								      		<div class="scnd-tb_txt">
								      			<h3>{{ $contest->title }}</h3>
								      			<p>{{ $contest->participants()->count() }} Participants</p>
								      		</div>
								      	</div>
									@endforeach
								@else
									<h4>No contest yet</h4>
								@endif	
					      	</div>
					    </div>
					    
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@include('flash_message')

@endsection

@section('script')

@endsection

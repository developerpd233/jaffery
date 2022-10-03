@extends('layouts.app')

@section('css')

 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

@endsection

@section('content')

<section class="hero hero-res">
    <div class="container-fluid">
		<ul class="hero-socials">
	        <li><a href="https://web.facebook.com/AreYouOneInAMillion?_rdc=1&_rdr" target="_blank"><i class="fa fa-facebook"></i></a></li>
	        <li><a href="https://www.instagram.com/pose2postcontest/" target="_blank"><i class="fa fa-instagram"></i></a></li>
	        <li><a href="https://twitter.com/posetopost" target="_blank"><i class="fa fa-twitter"></i></a></li>
	        <li><a href="https://www.youtube.com/channel/UCeK8wiSvSaZX2MRPNJxJIkw" target="_blank"><i class="fa fa-youtube-play"></i></a></li>
	    </ul>
        	<div class="hero-search hero-search-res">
            	<input type="search" id="searchbar" name="" placeholder="Search By Name">
            	<i class="fa fa-search" aria-hidden="true"></i>
            	<ul id='list'></ul>
            </div>
            <h1>Are You One In  A <span>Million?</span></h1>
            <h2>Are You Beautiful And Sexy And You Know It ?</h2>

            @auth
        	<a class="hero-upload" data-toggle="modal" data-target="#exampleModal" href="{{ url('participant') }}">Join the Contest<i class="fa fa-upload" aria-hidden="true"></i></a>
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	@if($annual_contest != null)
			      		<div class="form-check">
							<input type="radio" class="radio-input" name="radio-0" data-url="{{ url('participate/').'/'.$annual_contest->id }}">
							{{ $annual_contest->title." (Annual Contest)" }}
						</div>
			      	@endif
			      	@if($video_contest != null)
			      		<div class="form-check">
							<input type="radio" class="radio-input" name="radio-2" data-url="{{ url('participate/').'/'.$video_contest->id }}">
							{{ $video_contest->title." (Video Contest)" }}
						</div>
			      	@endif
			      	@foreach($mon_contests as $contest)
			      		<div class="form-check">
							<input type="radio" class="radio-input" name="radio-1" data-url="{{ url('participate/').'/'.$contest->id }}">
							{{ $contest->title." (Monthly Contest)" }}
						</div>
			      	@endforeach
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>
        @else
        	<a class="hero-upload" href="{{ url('login') }}">Join the Contest<i class="fa fa-upload" aria-hidden="true"></i></a>
        @endauth

        <div class="full">
        	<div class="box monthlyContestHead">
                <h5>Monthly Drawing</h5>
                <h6>$ {{ $monthly_contest != null ? $monthly_contest->amount : 0 }}</h6>
            </div>
      		<div class="box">
                <h5>{{ date("Y") }}<br> Video Contest</h5>
                <h6>$ {{ $video_contest != null ? $video_contest->amount : 0 }}</h6>
            </div>
            <div class="box">
                <h5>{{ date("Y") }}<br> Annual Contest</h5>
                <h6>$ {{ $annual_contest != null ? $annual_contest->amount : 0 }}</h6>
            </div>
        </div> 
    </div>
</section>

@if ($message = Session::get('error') || $message = Session::get('success'))
	@include('flash_message')
@endif

<section class="features">
    <div class="container-fluid">
    	<div class="box features-res">
    		<h2>Features</h2>
    		<ul>
	    		<li><a href="{{ url('country/United States') }}">Browse By Country</a></li>
	    		<li><a href="{{ url('state/Alaska') }}">Browse By State</a></li>
	    		<li><a href="{{ url('city/Akutan') }}">Browse By City</a></li>
	    		<li><a href="{{ url('profession/modeling') }}">Browse By Profession</a></li>
	    		<li>
	    			@php
	    				$ad_image = setting('site.advertise_image') != '' ? url('/public/storage/').'/'.setting('site.advertise_image') : 'https://posetopost.com/img/Group62ddd.png'; 
	    			@endphp
	    			<a href="{{ setting('site.advertise_url') }}">
	    				<img src="{{ $ad_image }}">
	    			</a>
	    			<!-- <a href="#">
	    				<img src="{{ url('img/Group62ddd.png') }}">
	    			</a> -->
	    		</li>
	    	</ul>
    	</div>

    	@if(isset($annual_contest) && $annual_contest)
    	<div class="box img">
    		<a href="{{ url('contests/').'/'.$annual_contest->id }}" class="annualParticipantsAnchor">
    			<img src="{{ Voyager::image($annual_contest->image) }}">
    			<h4>Annual</h4>
    		</a>
    	</div>
    	@endif

    	@if((isset($monthly_contest) && $monthly_contest) || (isset($video_contest) && $video_contest))
    	<div class="box img">
    		@if(isset($monthly_contest) && $monthly_contest)
			<a href="{{ url('contests/').'/'.$monthly_contest->id }}" class="monthlyParticipantsAnchor">
				<img src="{{ Voyager::image($monthly_contest->image) }}">
				<h4>Monthly</h4>
			</a>
			@endif

			@if(isset($video_contest) && $video_contest)
			<a href="{{ url('contests/').'/'.$video_contest->id }}" class="videoParticipantsAnchor">
				<img src="{{ Voyager::image($video_contest->image) }}">
				<h4>Video</h4>
			</a>
			@endif
    	</div>
    	@endif

    	<hr class="divider">
    </div>
</section>

<section class="recent">
	<div class="container-fluid">
		<h2>Recent Uploads</h2>
		<h6>Vote For Your Favourite</h6>

<?php
	// dd($participants);
?>

		<div class="full">
			@if(isset($participants[1]))
			<div class="box">
				@if(isset($participants[1]))
					<div class="img">
						<a href="{{ url('participant/').'/'.$participants[1]->id }}">
							<img src="{{ Voyager::image($participants[1]->image) }}">
							<div class="txt">
								<h5>{{ $participants[1]->name }}</h5>
								@auth
									@if(favouriteExist($participants[1]->id))
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[1]->id }}" data-status="1">
											<i class="fa fa-heart" aria-hidden="true"></i> 
										</a>
									@else
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[1]->id }}" data-status="0">
											<i class="fa fa-heart-o" aria-hidden="true"></i> 
										</a>
									@endif								
								@else
									<a href="{{ url('login') }}">
										<i class="fa fa-heart-o" aria-hidden="true"></i> 
									</a>
								@endauth
							</div>
						</a>
					</div>
				@endif
				@if(isset($participants[2]))
					<div class="img">
						<a href="{{ url('participant/').'/'.$participants[2]->id }}">
							<img src="{{ Voyager::image($participants[2]->image) }}">
							<div class="txt">
								<h5>{{ $participants[2]->name }}</h5>
								@auth
									@if(favouriteExist($participants[2]->id))
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[2]->id }}" data-status="1">
											<i class="fa fa-heart" aria-hidden="true"></i> 
										</a>
									@else
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[2]->id }}" data-status="0">
											<i class="fa fa-heart-o" aria-hidden="true"></i> 
										</a>
									@endif								
								@else
									<a href="{{ url('login') }}">
										<i class="fa fa-heart-o" aria-hidden="true"></i> 
									</a>
								@endauth
							</div>
						</a>
					</div>	
				@endif		
			</div>
			@endif
			@if(isset($participants[0]))
			<div class="box">
				<div class="img">
					<a href="{{ url('participant/').'/'.$participants[0]->id }}">
						<img src="{{ Voyager::image($participants[0]->image) }}">
						<div class="txt">
							<h5>{{ $participants[0]->name }}</h5>
							@auth
								@if(favouriteExist($participants[0]->id))
									<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[0]->id }}" data-status="1">
										<i class="fa fa-heart" aria-hidden="true"></i> 
									</a>
								@else
									<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[0]->id }}" data-status="0">
										<i class="fa fa-heart-o" aria-hidden="true"></i> 
									</a>
								@endif								
							@else
								<a href="{{ url('login') }}">
									<i class="fa fa-heart-o" aria-hidden="true"></i> 
								</a>
							@endauth
						</div>
					</a>
				</div>
			</div>
			@endif
			@if(isset($participants[3]))
			<div class="box">
				@if(isset($participants[3]))
					<div class="img">
						<a href="{{ url('participant/').'/'.$participants[3]->id }}">
							<img src="{{ Voyager::image($participants[3]->image) }}">
							<div class="txt">
								<h5>{{ $participants[3]->name }}</h5>
								@auth
									@if(favouriteExist($participants[3]->id))
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[3]->id }}" data-status="1">
											<i class="fa fa-heart" aria-hidden="true"></i> 
										</a>
									@else
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[3]->id }}" data-status="0">
											<i class="fa fa-heart-o" aria-hidden="true"></i> 
										</a>
									@endif								
								@else
									<a href="{{ url('login') }}">
										<i class="fa fa-heart-o" aria-hidden="true"></i> 
									</a>
								@endauth
							</div>
						</a>
					</div>	
				@endif
				@if(isset($participants[4]))
					<div class="img	">
						<a href="{{ url('participant/').'/'.$participants[4]->id }}">
							<img src="{{ Voyager::image($participants[4]->image) }}">
							<div class="txt">
								<h5>{{ $participants[4]->name }}</h5>
								@auth
									@if(favouriteExist($participants[4]->id))
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[4]->id }}" data-status="1">
											<i class="fa fa-heart" aria-hidden="true"></i> 
										</a>
									@else
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participants[4]->id }}" data-status="0">
											<i class="fa fa-heart-o" aria-hidden="true"></i> 
										</a>
									@endif								
								@else
									<a href="{{ url('login') }}">
										<i class="fa fa-heart-o" aria-hidden="true"></i> 
									</a>
								@endauth
							</div>
						</a>
					</div>	
				@endif				
			</div>
			@endif
			<hr class="divider">
			<div class="entries">
				<div class="ht">
					<h2>New Entries</h2>
					<h6>Vote For Your Favourite Or They Will Vote You,<br>This Is The Game</h6>
				</div>
				@auth
					<a href="">Vote Now</a>
				@else
					<a href="{{ url('login') }}">Vote Now</a>
				@endauth
			</div>
		</div>
	</div>
</section>

<section class="video-contest">
	<div class="container-fluid">
		<div class="full">
			<div class="boxes video-contest-box1">
				@if($video_participants->count() > 0)
					@foreach($video_participants as $key => $participant)
						@if($key < 5)
							<div class="box">
								<div class="img">
						    		<a href="{{ url('participant/').'/'.$participant->id }}"><img src="{{ Voyager::image($participant->image) }}"></a>
						    	</div>
						    	<div>
							    	<h5>{{ $participant->name }}</h5>
							    	<p>New Entrant</p>
						    	</div>
						    </div>
					    @endif
					@endforeach
				@else
					<h3>No entrants yet</h3>
				@endif
			</div>
			<div class="boxes video-contest-box">
				<h2>Video Contest</h2>
				<div class="items">
					@if($video_participants->count() > 0)
						@foreach($video_participants as $participant)
						<div class="asas">
						<div class="txt">

							<div class="heart-div">

								<h5>{{ $participant->name }}</h5>
								@auth
									@if(favouriteExist($participant->id))
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participant->id }}" data-status="1">
											<i class="fa fa-heart" aria-hidden="true"></i> 
										</a>
									@else
										<a href="javascript:void(0)" class="favourite" data-user="{{ auth()->user()->id }}" data-participant="{{ $participant->id }}" data-status="0">
											<i class="fa fa-heart-o" aria-hidden="true"></i> 
										</a>
									@endif								
								@else
									<a href="{{ url('login') }}">
										<i class="fa fa-heart-o" aria-hidden="true"></i> 
									</a>
								@endauth
							</div>

							@php
								$fb_url = "https://www.facebook.com/v6.0/dialog/share?app_id=439050034665686&channel_url=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df666867f02529%26domain%3Dwww.curiouspose.com%2Fparticipant%2F".$participant->id."%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%252Ff1a1a37b7258b74%26relation%3Dopener&display=popup&e2e=%7B%7D&fallback_redirect_uri=https%3A%2F%2Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%2F&href=https%3A%2F%2Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%2F%3Fshare%3D1&locale=en_US&next=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df360a2f994f278c%26domain%3Dwww.curiouspose.com%2Fparticipant%2F".$participant->id."%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%252Ff1a1a37b7258b74%26relation%3Dopener%26frame%3Dffed9c51f1458c%26result%3D%2522xxRESULTTOKENxx%2522&sdk=joey&version=v6.0";
								//$fb_url = "https://";
								$participant_url = "https://curiouspose.com/participant/".$participant->id;
							@endphp
							<div class="shareon" data-title="Pose2Post" data-url="{{ $participant_url }}"  data-media="{{ 'https://curiouspose.com/storage/'.$participant->image }}">
								  {{-- <a class="facebook"></a> --}}
								  <a class="facebook" href="javascript:void(0)" data-fb-url="{{ $fb_url }}" target="_blank"></a>
								  <a class="linkedin"></a>
								  <a class="pinterest"></a>
								  <a class="twitter"></a>
								  <a class="whatsapp"></a>
								  <a class="telegram"></a>
							</div>

						</div>

						<p class="video-votes">Votes {{$participant->votes->count()}}</p>

						<video controls>
							<source src="{{ Voyager::image($participant->video) }}">
						</video>
						</div>
						@endforeach
					@else
						<h3>No participants yet</h3>
					@endif
				</div>
			</div>
			<div class="boxes video-contest-box1">
				<h4>Recent Voters</h4>
				@if($video_votes->count() > 0)
					@foreach($video_votes as $key => $vote)
						@if($key < 4)
							<div class="box">
								<div class="img">
						    		<a href="{{ url('users/').'/'.$vote->user->id }}"><img src="{{ $vote->user->provider ? $vote->user->avatar : Voyager::image($vote->user->avatar) }}"></a>
						    	</div>
						    	<div>
						    		<h5>{{ $vote->user->name }}</h5>
							    	<p>Voted Now</p>
						    	</div>
						    </div>
					    @endif
					@endforeach
				@else
					<h3>No voters yet</h3>
				@endif
			</div>
		</div>
	</div>
</section>

@endsection

@section('script')

	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.iife.js" defer init></script>
	<script>

		$('.items').slick({
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1
		});

		$('.facebook').on('click',function(e){
			e.preventDefault();
			var fb_url = $(this).attr('data-fb-url');
			// alert(fb_url);
			window.open(fb_url,'name','height=500,width=600');
		})

		$('#searchbar').on('keyup',function(){
			search();
		})

		function search() {
		    let input = document.getElementById('searchbar').value
		    input=input.toLowerCase();
		    let x = document.getElementsByClassName('search');
		      
			if (input.length > 2) {
			    
				$.ajax({
	                type:"GET",
	                url: '{{ url("/search") }}',
	                data: {search: input},
	                success: function(data) {
	               		$('#list').empty();
	                    $(data).each(function( index , d ) {

	                    	if (typeof d.user_id === "undefined") {
							    var url = '{{ url("users/") }}'+'/'+d.id;
							    var image = '{{ url("storage/") }}'+'/'+d.avatar;

								$('#list').append(`<li><a href="${url}"><div class="img"><img src="${image}" alt=""></div><div><h4>${d.name}</h4><p>User</p></div></a></li>`);
							}
							else{
								var url = '{{ url("participant/") }}'+'/'+d.id;
								var image = '{{ url("storage/") }}'+'/'+d.image;

								$('#list').append(`<li><a href="${url}"><div class="img"><img src="${image}" alt=""></div><div><h4>${d.name}</h4><p>Participant</p></div></a></li>`);
							}
							
						});
	                }
	            });
			}
			else{
				$('#list').empty();
			}
		}

		$('.radio-input').on('click',function(){
			var url = $(this).data('url');
			window.location.href = url;
		})

		var monthlyContest = '';

		$.ajax({
            type:"GET",
            url: '{{ url("/monthlyContest") }}',
            success: function(data) 
            {
            	//console.log(data[0]);
           		monthlyContest = data;
            }
        });

        counter = 0,
            
        timer = setInterval(function(){
			codeAddress(monthlyContest[counter]);
			counter++

			if (counter > 5) {
				counter = 0
			}
        },3000);
	      
	    function codeAddress(record) {
	        // console.log(record);

	        $('.monthlyContestHead h5').html(`${record.title}<br> Monthly Contest`);
	        $('.monthlyContestHead h6').text(`$ ${record.amount}`);

	        // $('.monthlyContestAnchor h4').text(`${record.title}`);
	        // $('.monthlyContestAnchor img').attr('src', `${base_url}/storage/${record.image}`);
	        // $('.monthlyContestAnchor').attr('href', `${base_url}/contests/${record.id}`);
	    }

	    var annualParticipants = '';

		$.ajax({
            type:"GET",
            url: '{{ url("/annualParticipants") }}',
            success: function(data) 
            {
           		annualParticipants = data;
           		annual();
            }
        });

        function annual(){

        	if (annualParticipants.length >= 1) 
	        {
	        	annual_counter = 0,
	            
		        timer = setInterval(function(){
					annualParticipant(annualParticipants[annual_counter]);
					annual_counter++

					if (annual_counter > annualParticipants.length-1) {
						annual_counter = 0
					}
		        },3000);
			      
			    function annualParticipant(record) {
			        $('.annualParticipantsAnchor h4').text(`${record.name}`);
			        $('.annualParticipantsAnchor img').attr('src', `${base_url}/storage/${record.image}`);
			        $('.annualParticipantsAnchor').attr('href', `${base_url}/participant/${record.id}`);
			    }
	        }
        }

        var videoParticipants = '';

		$.ajax({
            type:"GET",
            url: '{{ url("/videoParticipants") }}',
            success: function(data) 
            {
           		videoParticipants = data;
           		video();
            }
        });

        function video(){

        	if (videoParticipants.length >= 1) 
	        {
	        	video_counter = 0,
	            
		        timer = setInterval(function(){
					videoParticipant(videoParticipants[video_counter]);
					video_counter++

					if (video_counter > videoParticipants.length-1) {
						video_counter = 0
					}
		        },3000);
			      
			    function videoParticipant(record) {
			        $('.videoParticipantsAnchor h4').text(`${record.name}`);
			        $('.videoParticipantsAnchor img').attr('src', `${base_url}/storage/${record.video_thumbnail}`);
			        $('.videoParticipantsAnchor').attr('href', `${base_url}/participant/${record.id}`);
			    }
	        }
        }

        var monthlyParticipants = '';

		$.ajax({
            type:"GET",
            url: '{{ url("/monthlyParticipants") }}',
            success: function(data) 
            {
           		monthlyParticipants = data;
           		monthly();
            }
        });

        function monthly(){

        	if (monthlyParticipants.length >= 1) 
	        {
	        	monthly_counter = 0,
	            
		        timer = setInterval(function(){
					monthlyParticipant(monthlyParticipants[monthly_counter]);
					monthly_counter++

					if (monthly_counter > monthlyParticipants.length-1) {
						monthly_counter = 0
					}
		        },3000);
			      
			    function monthlyParticipant(record) {
			        $('.monthlyParticipantsAnchor h4').text(`${record.name}`);
			        $('.monthlyParticipantsAnchor img').attr('src', `${base_url}/storage/${record.image}`);
			        $('.monthlyParticipantsAnchor').attr('href', `${base_url}/participant/${record.id}`);
			    }
	        }
        }

        $(document).ready(function() {
	  	
	  		Shareon.init();
		});

	</script>
@endsection
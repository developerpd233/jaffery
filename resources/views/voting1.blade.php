@extends('layouts.app')

@section('fb_title', $participant->name)
@section('fb_url', 'https://curiouspose.com/participant/'.$participant->id)
@section('fb_type', 'article')
@section('fb_image', 'https://curiouspose.com/storage/'.$participant->image )
@section('published_time', $participant->created_at)
@section('modified_time', $participant->updated_at)

@section('content')

@if ($message = Session::get('error') || $message = Session::get('success'))
	@include('flash_message')
@else
	@auth
			@if (!$exist)
				<div class="alert alert-danger alert-block mt-3 flash-alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>    
					<strong>This contest is not available now!</strong>
				</div>
			@elseif ($participant->status == 2)
				<div class="alert alert-danger alert-block mt-3 flash-alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>    
					<strong>This participant is past contest!</strong>
				</div>
			@endif
	@endauth
@endif

<section class="voting">

	{{-- *********** --}}

	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v14.0&appId=439050034665686&autoLogAppEvents=1" nonce="wpRSUEU6"></script>

	{{-- <div class="fb-share-button" data-href="https://curiouspose.com/participant/40" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fcuriouspose.com%2Fparticipant%2F40&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div> --}}

	{{-- <iframe src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fcuriouspose.com%2Fparticipant%2F40&layout=button&size=large&appId=439050034665686&width=77&height=28" width="77" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe> --}}

	{{-- *********** --}}



	<div class="container-fluid">

		<div class="lft">
			

			<div class="post">

				<a class="img" href="#">
					<img src="{{ Voyager::image($participant->image) }}">
				</a>
				<div class="fav-icon">
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
			</div>
		</div>

		<div class="rit">
			{{-- <div class="share">
				<span>SHARE</span>
			<ul>
				<li>
					<a href="mailto:?subject=Position2&body=http://position2.com" data-service="Email" target="_blank">
						Email
					</a>
				</li>
				<li>
					<a href="http://www.linkedin.com/shareArticle?mini=true&url=http://position2.com&title=Position2&summary=Position2 Inc.&source=Position2" data-service="LinkedIn" target="_blank">
						Linkedin
					</a>
				</li>
				<li>
					<a href="https://twitter.com/intent/tweet?text=Position2&url=http://position2.com" data-service="Twitter" target="_blank">
						</i>Twitter
					</a>
				</li>
				<li>
					@php
						$main_url = "https%3A%2F%2Fcuriouspose.com%2Fparticipant%2F".$participant->id;
						$fb = "https://www.facebook.com/sharer.php?u=".$main_url."&title=Pose2Post";
					@endphp
					<a href="{{ $fb }}" data-service="Facebook" target="_blank">
						Facebook
					</a>
				</li>
				<li>
					<a href="https://plus.google.com/share?url=http://position2.com" data-service="Google+" target="_blank">
						Google+
					</a>
				</li>
			</ul>
			</div> --}}
			@php
				$fb_url = "https://www.facebook.com/v6.0/dialog/share?app_id=439050034665686&channel_url=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df666867f02529%26domain%3Dwww.curiouspose.com%2Fparticipant%2F".$participant->id."%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%252Ff1a1a37b7258b74%26relation%3Dopener&display=popup&e2e=%7B%7D&fallback_redirect_uri=https%3A%2F%2Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%2F&href=https%3A%2F%2Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%2F%3Fshare%3D1&locale=en_US&next=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df360a2f994f278c%26domain%3Dwww.curiouspose.com%2Fparticipant%2F".$participant->id."%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fwww.curiouspose.com%2Fparticipant%2F".$participant->id."%252Ff1a1a37b7258b74%26relation%3Dopener%26frame%3Dffed9c51f1458c%26result%3D%2522xxRESULTTOKENxx%2522&sdk=joey&version=v6.0";
				$participant_url = "https://curiouspose.com/participant/".$participant->id;
				//$fb_url = "https://www.facebook.com/dialog/share?app_id=439050034665686&display=popup&href=".$participant_url."&redirect_uri=".$participant_url;
				
			@endphp
			{{-- <a class="facebook" href="{{ $u }}" target="_blank">Facebook</a> --}}
			{{-- 8334345987-harrison --}}
			{{-- <div class="fb-share-button" data-href="https://curiouspose.com/participant/40" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fcuriouspose.com%2Fparticipant%2F40&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div> --}}
			
			<div class="shareon mb-5" data-title="Pose2Post" data-url="{{ $participant_url }}" data-media="{{ 'https://curiouspose.com/storage/'.$participant->image }}">
				  {{-- <a class="facebook" data-title="Pose2Post" data-url="{{ $participant_url }}" data-media="{{ 'https://curiouspose.com/storage/'.$participant->image }}"></a> --}}
				  <a class="facebook" href="javascript:void(0)" data-fb-url="{{ $fb_url }}" target="_blank"></a> 
				  <a class="linkedin"></a>
				  <a class="pinterest"></a>
				  <a class="twitter"></a>
				  <a class="whatsapp"></a>
				  <a class="telegram"></a>
				  <!-- <a class="messenger" data-fb-app-id="FACEBOOK APP IDD"></a> -->
				</div>

			<h2>Recent Voters</h2>
			@if($voters->count() > 0)
				@foreach($voters as $voter)
					<div class="box">
			    		<a href="{{ url('users/').'/'.$voter->user->id }}"><img src="{{ $voter->user->provider ? $vote->user->avatar : Voyager::image($voter->user->avatar) }}"></a>
				    	<div>
				    		<h5>{{ $voter->user->name }}</h5>
					    	<p>Voted Now</p>
				    	</div>
				    </div>
				@endforeach
			@else
				<h3>No voters yet</h3>
			@endif
	    </div>
	</div>
</section>

<section class="voting-info">
	<div class="container-fluid">
		<!-- <form id="regForm" action="{{ route('make.payment') }}" method="POST"> -->
		<form id="regForm" action="{{ route('authorizeDotNet') }}" method="POST">
			@csrf
			
			<input type="hidden" name="participant_id" value="{{ $participant->id }}">
			<input type="hidden" name="c_url" value="{{ Request::url() }}">

			<div class="tab">
				<h2>Information</h2>
				<ul>
					<li>
						<span class="txt">Participant Name</span>
						<span class="txt">{{ $participant->name }}</span>
					</li>
					<li>
						<span class="txt">Position</span>
						<span class="txt">{{ $participant->position }}</span>
					</li>
					<li>
						<span class="txt">Contest Name</span>
						<span class="txt">{{ $participant->contest->title }}</span>
					</li>
					<li>
						<span class="txt">number</span>
						<span class="txt">{{ $participant->id }}</span>
					</li>
					{{-- <li>
						<span class="txt">City, State  or teritorry</span>
						<span class="txt">{{ isset($participant->user->city->name) ? $participant->user->city->name.', ' : '' }} {{ isset($participant->user->state->name) ? $participant->user->state->name : ''  }}</span>
					</li> --}}
					<li>
						<span class="txt">votes</span>
						<span class="txt">{{ $participant->votes->count() }}</span>
					</li>
					<li>
						<span class="txt">close time</span>
						<span class="txt">{{ date('d M, Y H:i A', strtotime($participant->contest->end_date)) }}</span>
					</li>
				</ul>
			</div>
			<div class="tab">
				<h2>Information</h2>
				<p>Please Ensure The Accuracy Of Your Email Adress As We Are Providing The Winning Partcipantsa Notice. That Could Be You. Voters Must Be In Numbers Only. Do Not Include Decimals O Commas When Entering Your Vote Amount Example Placing A Vote For $1,000.00 Should Be Typed As 1000 Warning: Due To Browser Delays Amd Potential High Volumes Of Voting Activity It Is Not A Advised To Wait Too Long T Place A Vote For Your Favourite Photo.</p>
				<ul>
					<li>
						<span class="txt">minimum</span>
						<span class="txt">$1</span>
					</li>
					<li>
						<span class="txt">maximum</span>
						<span class="txt input">
							<input type="hidden" name="participant_id" value="{{ $participant->id }}">
							<input type="number" name="amount" id="amount" placeholder="$ 1000" value="1">
							<div class="usd">USD</div>
						</span>
					</li>
					{{-- <li>
						<span class="txt">city, State  or teritorry</span>
						<span class="txt">{{ isset($participant->user->city->name) ? $participant->user->city->name.', ' : '' }} {{ isset($participant->user->state->name) ? $participant->user->state->name : ''  }}</span>
					</li> --}}
					<li>
						<span class="txt">country</span>
						<span class="txt">{{ isset($participant->user->country->name) ? $participant->user->country->name : '' }}</span>
					</li>
				</ul>
			</div>
			<div class="tab">
				<h2>Confirm Your Vote</h2>
				<ul>
					<h3>YOU VOTED AMOUNT $5</h3>
					<p>* All Winning Votes Are Considered A Commitment As Per Rules</p>
					<p>* Voting Rules Will Not Allow You To Withdraw Your Votes</p>
					<p>* Review Through Terms And Conditions Before Placing Your Vote</p>
				</ul>
			</div>

			@auth
				@if (!$exist)
                  
				@elseif ($participant->status == 2)

        		@else
        			<div class="vots_btn">
						<button type="button" id="nextBtn" onclick="nextPrev(1)">Vote Now</button>	
						<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
					</div>
        		@endif
      		@else
				<div class="vots_btn">
					<button type="button" id="loginBtn">Vote Now</button>
				</div>
			@endauth

			<div style="text-align:center;margin-top:40px;">
				<span class="step"></span>
				<span class="step"></span>
				<span class="step"></span>
				<span class="step"></span>
			</div>
		</form>
		<div class="photos">
			@if($participant->contest->type->slug == 'video')
                <h2>Video</h2>
				<div class="box">
	     			<video controls>
			  			<source src="{{ Voyager::image($participant->video) }}">
					</video>
				</div>       
            @else
            	<h2>Photos</h2>
				<div class="box">
					@if(count($images) > 0)
						@foreach($images as $image)
							<a class="img" href="#">
								<img src="{{ Voyager::image($image) }}">
							</a>
						@endforeach
					@else
						<h3>No images yet</h3>
					@endif
				</div>
   	        @endif
		</div>
		<div class="full_tab">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
			   	<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">description</a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">details</a>
			  </li>
			</ul>
			<div class="tab-content" id="myTabContent">
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			  	{{ $participant->description }}
			  </div>
			  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
			  	{{ $participant->details }}
			  </div>
			</div>
		</div>
	</div>
</section>

<section class="comments">
	<input type="hidden" name="participant_id" value="{{ $participant->id }}">
	<div class="container-fluid">
		<h2>Comments</h2>
		<form>

			<a href="#">
				@auth
					<img src="{{ auth()->user()->provider ? auth()->user()->avatar : Voyager::image(auth()->user()->avatar) }}"/>
				@else
					<img src=""/>
				@endauth
				
			</a>
			<textarea placeholder="Add yout comment" name="comment" id="comment" data-update="0"></textarea>
			
			@auth
				<input type="hidden" name="status" value="auth">
			@else
				<input type="hidden" name="status" value="login">
			@endauth
			
		</form>

		<div class="comment" id="comments_div">
			@foreach($comments as $comment)
				<div class="box">
					<div class="lft">
						<a href="#">
							<img src="{{ $comment->user->provider ? $vote->user->avatar : Voyager::image($comment->user->avatar) }}"/>
							<strong>{{ $comment->user->name }}</strong>
						</a>
						<p>{{ $comment->comment }}</p>
					</div>
					@auth
						@if(auth()->user()->id == $comment->user->id)
							<div class="rit">
								<i class="fa fa-ellipsis-v"></i>
								<ul>
									<li><a href="#" class="edit_comment" data-id="{{ $comment->id }}">Edit</a></li>
									<li><a href="#" class="delete_comment" data-id="{{ $comment->id }}">Delete</a></li>
								</ul>
							</div>
						@endif
					@endauth
				</div>
			@endforeach
		</div>
		<div id="move"></div>
	</div>
</section>

@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.iife.js" defer init></script>

<script>
	
	$(document).ready(function() {
	  	
		$('.facebook').on('click',function(e){
			e.preventDefault();
			var fb_url = $(this).attr('data-fb-url');
			// alert(fb_url);
			window.open(fb_url,'name','height=500,width=600');
		})

		// $(".share").mouseenter(function() {
		// 	$(this).find("ul").show()
		// }).mouseleave(function() {
		// 	$(this).find("ul").hide()
		// });

		// $(".share a").click(function(e) {
		// 	var dis = $(this)
		// 	if(dis.attr("data-service") != "Email") {
		// 		e.preventDefault();
		// 		var url = dis.attr("href");
		// 		window.open(url,'name','height=500,width=500');
		// 	}
		// })

	  	Shareon.init();

	  	var amount = $('#amount').val();
		$('ul h3').empty().text('YOU VOTED AMOUNT $' + amount);

	  	$('#amount').on('change',function(){
	  		var amount = $('#amount').val();
			$('ul h3').empty().text('YOU VOTED AMOUNT $' + amount);
	  	});

	  	$('#loginBtn').on('click',function(){
			window.location.href = '{{ url("login") }}';
	  	});

	  	$(document).on('click','.comment .rit i',function(){
		    $(this).next().toggle();
		});
		
	});

</script>

<script>

  $(document).ready(function(){
    
    $("body").on('keypress','#comment',function(event){
      
      	var keycode = (event.keyCode ? event.keyCode : event.which);

	    if(keycode == '13'){
	  
	  		if ($('input[name="status"]').val() == 'auth') {

	  			var token   = $('meta[name="csrf-token"]').attr('content');
			    var participant_id = $('input[name="participant_id"]').val();
			    var comment = $('#comment').val();

			    $.ajax({
			      url: '{{ url("/comments") }}',
			      type: 'POST',
			      data: {
			        participant_id: participant_id,
			        comment: comment,
			        _token: token
			      },
			      success: function(response) {
			        
			        console.log(response);

			        if(response.code == 200) {

			        	var comment = response.data[0];
			        	var user = response.data[1];

			          	if(user.provider != null){
			          		var image = user.avatar;
			          	}else{
			          		var image = base_url+'/storage/'+user.avatar;
			          	}
			          	

			          	$('#comment').val('');
			            $('#comments_div').append(`<div class="box">
							<div class="lft">
								<a href="#">
									<img src="${ image }"/>
									<strong>${user.name}</strong>
								</a>
								<p>${comment.comment}</p>
							</div>
							<div class="rit">
								<i class="fa fa-ellipsis-v"></i>
								<ul>
									<li><a href="#" class="edit_comment" data-id="${comment.id}">Edit</a></li>
									<li><a href="#" class="delete_comment" data-id="${comment.id}">Delete</a></li>
								</ul>
							</div>
						</div>`);
					    
					    $('.toast .toast-body').empty().html(response.message);    
			            $('.toast').toast('show');
			        }
			      },
			      error: function(response) {
			        console.log(response);
			      }
			    });

	  		} 
	  		else {
	  			window.location.href = '{{ url("login") }}';
	  		}      
	    }
    });

	$("body").on('click','.delete_comment',function(e){
	    e.preventDefault();
	    
	    var token   = $('meta[name="csrf-token"]').attr('content');
	    var url = '{{ url("/comments/") }}'+ '/' + $(this).data('id');
	    var box = $(this).parent().parent().parent().parent();

	    $.ajax({
	      url: url,
	      type: 'Delete',
	      data: {
	        _token: token
	      },
	      success: function(response) {
	        if(response.code == 200) {
	        	console.log('delete');
	        	box.remove();
	        	$('.toast .toast-body').empty().html(response.message);    
		        $('.toast').toast('show');
	        }
	      },
	      error: function(response) {
	        console.log(response);
	      }
	    });
	});

  });

  	$("body").on('click','.edit_comment',function(e){
	    e.preventDefault();
	    
	    var left = $(this).parent().parent().parent().siblings('.lft');
	    var id = $(this).data('id');
	    var comment = left.children('p').text();

	    left.children('p').remove();
	    left.append(`<textarea name="edit_comment" id="edit_comment" data-id="${id}">${comment}</textarea>`);
  	});

  	$("body").on('keypress','#edit_comment',function(event){
      
      	var element = $(this);
      	var keycode = (event.keyCode ? event.keyCode : event.which);

	    if(keycode == '13'){
	  
	    		var token   = $('meta[name="csrf-token"]').attr('content');
			    var comment = $('#edit_comment').val();
			    var update_id   = $('#edit_comment').data('id');
			    var participant_id = $('input[name="participant_id"]').val();
			    var url = '{{ url("/comments/") }}'+'/'+update_id;

			    $.ajax({
				      url: url,
				      type: 'POST',
				      data: {
				      	participant_id: participant_id,
				        comment: comment,
				        _token: token,
				        _method: 'PATCH'
				      },
				      success: function(response) {
				        
				        console.log(response);

				        if(response.code == 200) {

				        	element.parent().append(`<p>${comment}</p>`);
						    element.remove();

						    $('.toast .toast-body').empty().html(response.message);    
				            $('.toast').toast('show');
				        }
				      },
				      error: function(response) {
				        console.log(response);
				      }
				});

	    }
    });

</script>

@endsection

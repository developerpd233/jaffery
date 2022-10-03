@php
	$seg = Request::segment(1);
@endphp

@if($seg == null || $seg == 'login' || $seg == 'register')

@else

<div class="b_icons">
	<a href="https://web.facebook.com/AreYouOneInAMillion?_rdc=1&_rdr"><i class="fa fa-facebook" aria-hidden="true"></i></a>
	<a href="https://www.instagram.com/pose2postcontest/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
	<a href="https://twitter.com/posetopost"><i class="fa fa-twitter" aria-hidden="true"></i></a>
	<a href="https://www.youtube.com/channel/UCeK8wiSvSaZX2MRPNJxJIkw"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
	<!-- <a href="#"><i class="fa fa-comment" aria-hidden="true"></i></a>
	<a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
	<a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i></a>
	<a href="#"><i class="fa fa-link" aria-hidden="true"></i></a> -->
</div>

@endif

<footer>
	<div class="container-fluid">
		<div class="ftr_logo">
			<a href="{{ url('/') }}"><img src="{{ url('img/Group759.png') }}"></a>
			<div class="ftr_icons">
				<a href="https://web.facebook.com/AreYouOneInAMillion?_rdc=1&_rdr" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				<a href="https://www.instagram.com/pose2postcontest/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
				<a href="https://twitter.com/posetopost" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
				<a href="https://www.youtube.com/channel/UCeK8wiSvSaZX2MRPNJxJIkw" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
			</div>
		</div>
		<div class="ftr_nav">
			<h2>Quick Links</h2>
			<ul>
				<li><a href="{{ url('') }}">Home</a></li>
				<li><a href="{{ url('profession/modeling') }}">Professions</a></li>
				<li><a href="{{ url('contests') }}">Pose 2 Post</a></li>
				<li><a href="{{ url('contestants') }}">Contestants</a></li>
				<li><a href="{{ url('country/United States') }}">Country</a></li>
				<li><a href="{{ url('winners') }}">Winners</a></li>
				<li><a href="{{ url('state/Alaska') }}">State</a></li>
			</ul>
		</div>
		<div class="ftr_nav">
			<div class="ftr_sub-nav">
				<ul>
					<!-- <li><a href="{{ url('') }}">Payment Option</a></li> -->
					<!-- <li><a href="{{ url('') }}">Help</a></li> -->
					<li><a href="{{ url('contact') }}">Contact Us</a></li>
					<li><a href="{{ url('faq') }}">FAQ</a></li>
					<li><a href="{{ url('terms-and-conditions') }}">Terms & Conditions</a></li>
					<!-- <li><a href="{{ url('') }}">System Status</a></li> -->
					<li><a href="{{ url('privacy-and-security-policy') }}">Privacy & Security Policy</a></li>
					<li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
					<li><a href="{{ url('security-policy') }}">Security Policy</a></li>
					<li><a href="{{ url('return-and-refund-policy') }}">Return & Refund Policy</a></li>
					<li><a href="{{ url('shipping-policy') }}">Shipping Policy</a></li>
				</ul>
			</div>
		</div>	
		<p>Copyright © 2010-2021 Freepik Company S.L. All Rights Reserved</p>
	</div>
</footer>
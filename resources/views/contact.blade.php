@extends('layouts.app')
@section('meta_title', 'contact')
@section('meta_description', 'contact')
@section('page_title', 'Contact Us')

@section('content')

@if ($message = Session::get('error') || $message = Session::get('success'))
	@include('flash_message')
@endif

<section class="contct-sec">
	<div class="main-contct">
		<form action="{{ url('contact') }}" method="POST">
			@csrf
		
			<div class="contct">
				<div class="cntct-fields">
					<label>First Name*</label>
					<input type="name" name="firstname" required>
				</div>
				<div class="cntct-fields">
					<label>Email Address*</label>
					<input type="email"  name="email" required>
				</div>
				<div class="cntct-fields">
					<label>Company Name*</label>
					<input type="name" name="company" required>
				</div>
				<div class="cntct-fields">
					<label>Country*</label>
					<input type="name" name="country" required>
				</div>
			</div>
			<div class="contct2">
				<div class="cntct-fields">
					<label>Last Name*</label>
					<input type="name" name="lastname" required>
				</div>
				<div class="cntct-fields">
					<label>Phone Number*</label>
					<input type="text" name="phone" required>
				</div>
				<div class="cntct-fields">
					<label>Company Website*</label>
					<input type="url" name="website" required>
				</div>
				<!-- <div class="cntct-fields">
					<label>Company Website</label>
					<input type="name" name="name">
				</div> -->
				
			</div>
			<div class="cntct-fields msg">
				<label>Description</label>
				<textarea name="description" cols="50" rows="5" required></textarea>
			</div>
			<div class="cntc-btn">
				<button type="submit">Submit</button>
			</div>
		</form>
	</div>	
</section>

@endsection
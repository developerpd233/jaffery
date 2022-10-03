@extends('layouts.app')
@section('page_title', 'Register')

@php
    $countries = TCG\Voyager\Models\Country::all();
    $professions = TCG\Voyager\Models\Profession::all();
@endphp

@section('content')

<section class="inner_header">
    <div class="container-fluid">
        <ul class="hero-socials">
            <li><a href="https://web.facebook.com/AreYouOneInAMillion?_rdc=1&amp;_rdr"><i class="fa fa-facebook"></i></a></li>
            <li><a href="https://www.instagram.com/pose2postcontest/"><i class="fa fa-instagram"></i></a></li>
            <li><a href="https://twitter.com/posetopost"><i class="fa fa-twitter"></i></a></li>
            <li><a href="https://www.youtube.com/channel/UCeK8wiSvSaZX2MRPNJxJIkw"><i class="fa fa-youtube-play"></i></a></li>
        </ul>
        <h2>Register</h2>
    </div>
</section>

<section class="regsec3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                {!! RecaptchaV3::field('register') !!}

                <div class="reg_form">
                    <div class="form_1">
                        <h2>Account Details</h2>
                        <div class="fields">
                        	<label>User Name</label>
                        	<input id="username" type="text" class="@error('username') is-invalid @enderror" name="username" value="{{ old('username') }}"  autocomplete="name" autofocus required>	
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="fields">
                        	<label>Email</label>
                        	<input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="fields">
                        	<label>Password</label>
                        	<input id="password" type="password" class="@error('password') is-invalid @enderror" name="password"  autocomplete="new-password" required>	
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="fields">
                        	<label>Display Name Required</label>
                        	<input id="display_name" type="text" class="@error('display_name') is-invalid @enderror" name="display_name" value="{{ old('display_name') }}"  autocomplete="display_name" autofocus required>	
                            @error('display_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
	                    </div>

                    </div>
                    <div class="form_main">
	                    <div class="form_1_1">
	                        <h2>Profile Details</h2>
	                        <div class="fields">
	                        	<label>Fully Legal Name</label>
	                        	<input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        	
	                        </div>
                            <div class="fields">
                                <label>Address</label>
                                <input id="address" type="text" class="@error('address') is-invalid @enderror" name="address" value="{{ old('address') }}"  autocomplete="address" autofocus>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
	                        <div class="fields">
	                        	<label>Country</label>
	                        	<select class="select2 select_fields" id="country_id" name="country_id">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
								
	                        </div>
	                        <div class="fields">
	                        	<label>State</label>
	                        	<select class="select2 select_fields" id="state_id" name="state_id"></select>
                                @error('state_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
								
	                        </div>
	                        <div class="fields">
	                        	<label>City</label>
	                        	<select class="select2 select_fields" id="city_id" name="city_id"></select>
                                @error('city_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
								
	                        </div>
	                        <div class="fields">
	                        	<label>Date Of Birth</label>
	                        	<input id="date_of_birth" type="date" class="@error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}"  autocomplete="date_of_birth" autofocus required>
                                @error('date_of_birth')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        	
	                        </div>
	                        <div class="fields">
	                        	<label>Bio</label>
	                        	<textarea id="bio" class="@error('bio') is-invalid @enderror" name="bio" autocomplete="bio" autofocus>{{ old('bio') }}</textarea>	
                                @error('bio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        	
	                        </div>
	                        <div class="fields">
	                        	<label>Facebook Profile Page</label>
	                        	<input id="facebook" type="url" class="@error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook') }}"  autocomplete="facebook" autofocus>
                                @error('facebook')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        	
	                        </div>
	                        <div class="fields">
	                        	<label>Linkedin Profile Page</label>
	                        	<input id="linkedin" type="url" class="@error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin') }}"  autocomplete="linkedin" autofocus>
                                @error('linkedin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror	
	                        	
	                        </div>
	                        <div class="fields">
	                        	<label>Twitter Profile Page</label>
	                        	<input id="twitter" type="url" class="@error('twitter') is-invalid @enderror" name="twitter" value="{{ old('twitter') }}"  autocomplete="twitter" autofocus>
                                @error('twitter')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        	
	                        </div>
	                        <div class="fields">
	                        	<label>Instagram Profile Page</label>
	                        	<input id="instagram" type="url" class="@error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram') }}"  autocomplete="instagram" autofocus>	
                                @error('instagram')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        	
	                        </div>
	                        <div class="fields">
	                        	<label>Profession</label>
	                        	<select class="select2 select_fields" id="profession" name="profession">
                                    @foreach ($professions as $profession)
                                        <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                                    @endforeach
                                </select>	
                                @error('profesion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                        </div>
                            <div class="fields other_profession" style="display: none;">
                                <input id="other_profession" type="other_profession" class="@error('other_profession') is-invalid @enderror" name="other_profession" value="{{ old('other_profession') }}" placeholder="Please enter other profession">  
                            </div>
                            <div class="fields">
                                <label>Image</label>
                                <input type="file" class="" id="image" name="image" required >  
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- <div class="fields parent_check" style="display: none;">
                                <div class="">
                                    <input type="checkbox" id="parent_check" name="parent_check" value="1" required>
                                    <label for="html">Parent Check</label><br>
                                </div>
                            </div> -->
                            <div class="fields">
                                <div class="logn_radiobtn">
                                    <input type="radio" id="html" name="fav_language" value="HTML" required>
                                    <label for="html">I have read and agree to this site's <a href="{{ url('terms-and-conditions') }}">Terms & Conditions</a> And <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                                    </label><br>
                                </div>
                            </div>
                            <div class="fields">
                                @error('g-recaptcha-response')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
	                    </div>
                    </div>
                    <div class="sbmit_form">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    	<!-- <a href="#">Submit</a> -->
                    </div>
                </div>

            </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

<script>
    $(document).ready(function() {
        
        $('.select2').select2();

        $("select#country_id").change(function(){
            var selectedCountry = $("#country_id option:selected").val();
            setStates(selectedCountry);
        });

        $("select#state_id").change(function(){
            var selectedState = $("#state_id option:selected").val();
            setCities(selectedState);
        });

        $("select#profession").change(function(){
            var selectedPofession = $("#profession option:selected").text();
            //alert(selectedPofession);
            if (selectedPofession == 'Others') {
                $(".other_profession").css('display','block');
                $("#other_profession").attr('required',true);
            } else {
                $(".other_profession").css('display','none');
                $("#other_profession").attr('required',false);
                $("#other_profession").val('');
            }
        });
    });

    

    function setStates(id) {
        
        $.get('{{ url("/getStates/") }}/'+id, function(data, status){
            
            var data1 = $.map(data, function (obj) {
                obj.text = obj.text || obj.name;
                return obj;
            });

            $('select#state_id').select2().empty();
            $('select#state_id').select2();
            $('select#state_id').select2({ data: data1 });
        });
    }

    function setCities(id) {
        
        $.get('{{ url("/getCities/") }}/'+id, function(data, status){
            
            var data1 = $.map(data, function (obj) {
                obj.text = obj.text || obj.name;
                return obj;
            });

            $('select#city_id').select2().empty();
            $('select#city_id').select2();
            $('select#city_id').select2({ data: data1 });
        });
    }

    function underAgeValidate(birthday) {
       // it will accept two types of format yyyy-mm-dd and yyyy/mm/dd
       var optimizedBirthday = birthday.replace(/-/g, "/");

       //set date based on birthday at 01:00:00 hours GMT+0100 (CET)
       var myBirthday = new Date(optimizedBirthday);

       // set current day on 01:00:00 hours GMT+0100 (CET)
       var currentDate = new Date().toJSON().slice(0, 10) + ' 01:00:00';

       // calculate age comparing current date and borthday
       var myAge = ~~((Date.now(currentDate) - myBirthday) / (31557600000));

       if (myAge < 16) {
            $('.parent_check').remove();
            $('.form_1_1').append(`<div class="fields parent_check">
                                <div class="">
                                    <input type="checkbox" id="parent_check" name="parent_check" value="1" required>
                                    <label for="html">Parent Check</label><br>
                                </div>
                            </div>`)
       } else {
            $('.parent_check').remove();
       }
    }
    $("#date_of_birth").on("change paste keyup", function() {

        var datestring = $(this).val();
        underAgeValidate(datestring);
    });

</script>

@endsection

@extends('layouts.app')
@section('page_title', 'Login')

@section('content')
<section class="inner_header">
    <div class="container-fluid">
        <ul class="hero-socials">
            <li><a href="https://web.facebook.com/AreYouOneInAMillion?_rdc=1&amp;_rdr"><i class="fa fa-facebook"></i></a></li>
            <li><a href="https://www.instagram.com/pose2postcontest/"><i class="fa fa-instagram"></i></a></li>
            <li><a href="https://twitter.com/posetopost"><i class="fa fa-twitter"></i></a></li>
            <li><a href="https://www.youtube.com/channel/UCeK8wiSvSaZX2MRPNJxJIkw"><i class="fa fa-youtube-play"></i></a></li>
        </ul>
        <h2>Login</h2>
    </div>
</section>

<section class="loginsec3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="logn_form">
                    <h2 class="ac-details">Account Details</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        {!! RecaptchaV3::field('login') !!}
                        <div class="login-form_1">
                            <div class="login_fields">
                                <label>Email</label>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="login_fields">
                                <label>Password</label>
                                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <!-- @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"><span>Forget Password?</span></a>
                                @endif -->
                            </div>

                            <!-- <div class="login_fields"> -->
                                <div class="form-check">
                                       <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                       <label class="form-check-label" for="remember">
                                              {{ __('Remember Me') }}
                                       </label>
                                </div>

                                <div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}"><span>Forget Password?</span></a>
                                    @endif
                                </div>
                            <!-- </div> -->
                            <!-- <div class="login_fields terms-field">
                                <input type="checkbox" name="terms" id = "terms"><label for="terms"> Terms & Conditions</label>
                            </div> -->
                            <div class="login_fields">
                                @error('g-recaptcha-response')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- <div class="logn_radiobtn">
                                <input type="radio" id="html" name="fav_language" value="HTML" required>
                                <label for="html">Terms & Conditions</label><br>
                            </div> -->
                        </div>
                    
                        <div class="logins_btn">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Sign In') }}
                            </button>
                            <!-- <a href="#">Sign In</a> -->
                            <a class="btn-2" href="{{ route('register') }}">Create An Account</a>
                        </div>

                    </form>
                    <div class="sign_us">
                        <h2>Sign In With ?</h2>
                        <a href="{{ route('provider','facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <!-- <a href="{{ route('provider','twitter') }}"><i class="fa fa-twitter" aria-hidden="true"></i></a> -->
                        <a href="{{ route('provider','linkedin') }}"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        <!-- <a href="{{ route('provider','instagram') }}"><i class="fa fa-instagram" aria-hidden="true"></i></a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<header>
    <div class="container-fluid">
        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ url('img/Group58.png') }}"></a>
        </div>

        <div class="navi">
            <i class="fa fa-bars" aria-hidden="true"></i>
            <ul>
                <li><a href="{{ url('') }}">Home</a></li>
                <!-- <li><a href="{{ url('contests') }}">Post 2 Post</a></li> -->
                <li><a href="{{ url('contests') }}">Contests</a></li>
                <li><a href="{{ url('country/United States') }}">Country</a></li>
                <li><a href="{{ url('state/Alaska') }}">State</a></li>
                <li><a href="{{ url('profession/modeling') }}">Professions</a></li>
                <li><a href="{{ url('contestants') }}">Contestants</a></li>
                <li><a href="{{ url('winners') }}">Winners</a></li>
                <li><a href="{{ url('favorites') }}">Favorites</a></li>	
            </ul>
        </div>

        <div class="hdr-btn">
            @guest
                <i class="fa fa-sign-in" aria-hidden="true"></i>
                <div class="sign-in">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}">Login</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                 </div>
            @else
           
            <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">{{auth()->user()->name}}</button>
                <button onclick="myFunction()" class="dropbtn mob-user"><i class="fa fa-user"></i></button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="{{ url('admin/user_dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i>Dashboard</a>  
                    <a href="{{ url('admin/user_profile') }}"><i class="fa fa-user" aria-hidden="true"></i>Profile</a>
                    <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="fa fa-power-off" aria-hidden="true"></i>Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            @endguest
        </div>
    </div>
</header>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="contest,participate,participant,compitition">
    <meta name="author" content="Pose 2 Post">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="index,follow">

    <!-- Primary Meta Tags -->
    <title>@yield('meta_title', 'Pose 2 Post')</title>
    <meta name="title" content="@yield('fb_title', 'Pose 2 Post')">
    <meta name="description" content="Pose 2 Post - Are You One In A Million.">

    <!-- Open Graph / Facebook -->
    <meta property="fb:app_id" content="439050034665686">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:url" content="@yield('fb_url', 'https://posetopost.com')">
    <meta property="og:title" content="@yield('fb_title', 'Pose 2 Post')">
    <meta property="og:description" content="Pose 2 Post - Are You One In A Million.">
    <meta property="og:image" content="@yield('fb_image', url('/img/Group58.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="@yield('fb_url', 'https://posetopost.com')">
    <meta property="twitter:title" content="@yield('fb_title', 'Pose 2 Post')">
    <meta property="twitter:description" content="Pose 2 Post - Are You One In A Million.">
    <meta property="twitter:image" content="@yield('fb_image', url('/img/Group58.png'))">


    <link rel="icon" type="image/x-icon" href="{{ url('/img/Group58.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet"></style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/shareon@2/dist/shareon.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/responsive.css') }}">

    <!-- End Styles -->

    @php
      $segment = Request::segment(1);
    @endphp

    {!! RecaptchaV3::initJs() !!}
    @if ($segment == 'login' || $segment == 'register')
    @endif

    @yield('css')

    <script type="text/javascript">
      var base_url = '{{ url('') }}';
    </script>
</head>
<body>
    <div id="app">
        @include('layouts/header')

        @if ($segment != 'login' && $segment != 'register' && $segment != '')
          <section class="inner_header">
            <div class="container-fluid">
              <h2>@yield('page_title', 'Pose 2 Post')</h2>
            </div>
          </section>
        @endif

        @yield('content')

        @include('layouts/footer')

        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; bottom: 1rem; right: 1rem;">

      <div class="toast-body" style="background-color: #38c172;
    color: white">
        Favourite request, successfully completed.
      </div>
    </div>
    </div>
</body>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
<script src="{{ url('/js/custom.js') }}"></script>
<!-- End Scripts -->


<script>

  /*for login drop down*/

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab


function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Vote Now";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}


function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  // if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

<script>

  $(document).ready(function(){
    $("body").on('click','.favourite',function(){

      var fav = $(this);
      var user = fav.data('user');
      var participant = fav.data('participant');
      var status = $(this).data('status');

      if (status == '0') {
        createFavourite(user,participant,status,fav);
      }
      else{
        deleteFavourite(user,participant,status,fav);
      }

    });
  });

  function createFavourite(user,participant,status,fav) {

    let _url     = '{{ url("/favourites") }}';
    let _token   = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
      url: _url,
      type: "POST",
      data: {
        participant_id: participant,
        _token: _token
      },
      success: function(response) {
        if(response.code == 200) {
          fav.parent().append(`<a href="javascript:void(0)" class="favourite" data-user="${user}" data-participant="${participant}" data-status="1">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                  </a>`);
          fav.remove();
          $('.toast').toast('show');
        }
      },
      error: function(response) {
        console.log(response);
        fav.parent().append(`<a href="javascript:void(0)" class="favourite" data-user="${user}" data-participant="${participant}" data-status="0">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                  </a>`);
        fav.remove();
      }
    });
  }

  function deleteFavourite(user,participant,status,fav) {
    console.log('sd');
    let _url = '{{ url("/favourite-delete") }}';
    let _token   = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
      url: _url,
      type: 'POST',
      data: {
        participant_id: participant,
        _token: _token
      },
      success: function(response) {
        if(response.code == 200) {
          fav.parent().append(`<a href="javascript:void(0)" class="favourite" data-user="${user}" data-participant="${participant}" data-status="0">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                  </a>`);
          fav.remove();
          $('.toast').toast('show');
        }
      },
      error: function(response) {
        console.log(response);
        fav.parent().append(`<a href="javascript:void(0)" class="favourite" data-user="${user}" data-participant="${participant}" data-status="1">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                  </a>`);
        fav.remove();
      }
    });
  }

</script>

@yield('script')

</html>

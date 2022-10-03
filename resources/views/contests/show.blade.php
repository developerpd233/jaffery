@extends('layouts.app')
@section('meta_title', 'Contest')
@section('meta_description', 'Contest')
@section('page_title', $contest->title)
@section('content')

<section class="photo-sec">
    <div class="container-fluid">

        <div class="hero-search">
            <input type="name" id="searchbar" name="" placeholder="Search Participant">
            <i class="fa fa-search" aria-hidden="true"></i>
            <ul id="list"></ul>
        </div>

        <div class="photo-sec-btn">
            <h2 class="photo-sec-h2">{{ $contest->title }}</h2>
            <a class="hero-upload" href="{{ url('participate/').'/'.$contest->id }}">Join the Contest</a>
        </div>
        

        @if($participants->count() > 0)
        @if(isset($participants[0]))
        <div class="half">
            <div class="box">
                <a class="img" href="{{ url('participant/').'/'.$participants[0]->id }}"><img src="{{ Voyager::image($participants[0]->image) }}"></a> 
                <div class="circle">
                    <a href="{{ url('users/').'/'.$participants[0]->user->id }}"><img src="{{ $participants[0]->user->provider ? $participants[0]->user->avatar : Voyager::image($participants[0]->user->avatar) }}"></a>
                    <h5>{{$participants[0]->name}}<br>1st Positon, {{$participants[0]->votes->count()}} Votes</h5>
                </div>
            </div>
        </div>
        @endif
        @if(isset($participants[1]))
        <div class="half">
            @if(isset($participants[1]))
            <div class="box">
                <a class="img" href="{{ url('participant/').'/'.$participants[1]->id }}"><img src="{{ Voyager::image($participants[1]->image) }}"></a>
                <div class="circle">
                    <a href="{{ url('users/').'/'.$participants[1]->user->id }}"><img src="{{ $participants[1]->user->provider ? $participants[1]->user->avatar : Voyager::image($participants[1]->user->avatar) }}"></a>
                    <h5>{{$participants[1]->name}}<br>2nd Positon, {{$participants[1]->votes->count()}} Votes</h5>
                </div>
            </div>
            @endif
            @if(isset($participants[2]))
            <div class="box">
                <a class="img" href="{{ url('participant/').'/'.$participants[2]->id }}"><img src="{{ Voyager::image($participants[2]->image) }}"></a>
                <div class="circle">
                    <a href="{{ url('users/').'/'.$participants[2]->user->id }}"><img src="{{ $participants[2]->user->provider ? $participants[2]->user->avatar : Voyager::image($participants[2]->user->avatar) }}"></a>
                    <h5>{{$participants[2]->name}}<br>3rd Positon, {{$participants[2]->votes->count()}} Votes</h5>
                </div>
            </div>
            @endif
            @if(isset($participants[3]))
            <div class="box">
                <a class="img" href="{{ url('participant/').'/'.$participants[3]->id }}"><img src="{{ Voyager::image($participants[3]->image) }}"></a>
                <div class="circle">
                    <a href="{{ url('users/').'/'.$participants[3]->user->id }}"><img src="{{ $participants[3]->user->provider ? $participants[3]->user->avatar : Voyager::image($participants[3]->user->avatar) }}"></a>
                    <h5>{{$participants[3]->name}}<br>4th Positon, {{$participants[3]->votes->count()}} Votes</h5>
                </div>
            </div>
            @endif
            @if(isset($participants[4]))
            <div class="box">
                <a class="img" href="{{ url('participant/').'/'.$participants[4]->id }}"><img src="{{ Voyager::image($participants[4]->image) }}"></a>
                <div class="circle">
                    <a href="{{ url('users/').'/'.$participants[4]->user->id }}"><img src="{{ $participants[4]->user->provider ? $participants[4]->user->avatar : Voyager::image($participants[4]->user->avatar) }}"></a>
                    <h5>{{$participants[4]->name}}<br>5th Positon, {{$participants[4]->votes->count()}} Votes</h5>
                </div>
            </div>
            @endif
        </div>
        @endif
        @else
        <h3>No participant yet on this location.</h3>   
        @endif

        @if($participants->count() > 5)
            <div class="full">
                @foreach($participants as $key => $participant)
                    @if($key > 4)
                        <div class="box">
                            <a class="img" href="{{ url('participant/').'/'.$participant->id }}"><img src="{{ Voyager::image($participant->image) }}"></a>
                            <div class="circle">
                                <a href="{{ url('users/').'/'.$participant->user->id }}"><img src="{{ $participant->user->provider ? $participant->user->avatar : Voyager::image($participant->user->avatar) }}"></a>
                                <h5>{{$participant->name}}<br>{{$key+1}}th Positon, {{$participant->votes->count()}} Votes</h5>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>   
        @endif

    </div>
</section>

@endsection

@section('script')
<script>

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
                data: {search: input,type:'participant',contest_id:'{{ $contest->id }}'},
                success: function(data) {
                    $('#list').empty();
                    $(data).each(function( index , d ) {

                        var url = '{{ url("participant/") }}'+'/'+d.id;
                            $('#list').append(`<a href="${url}" class="search">${d.name}</a>`);
                        
                    });
                }
            });
        }
        else{
            $('#list').empty();
        }
    }
</script>
@endsection
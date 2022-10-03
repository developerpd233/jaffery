@extends('layouts.app')
@section('meta_title', 'Contestants')
@section('meta_description', 'Contestants')
@section('page_title', 'Contestants')
@section('content')

@section('css')
<style>
    
</style>
@endsection

@foreach($contests as $key => $contest)

    @if(count(featuredRecords($contest->id)) > 0)
    <section class="photo-sec">
        <div class="container-fluid">
            <div></div>
            <h2>{{ $contest->title }}</h2>
            @php
                $participants = featuredRecords($contest->id);
            @endphp
            @if($participants->count() > 0)
            @if(isset($participants[0]))

            
            <div class="half">
            <div class="box" data-part-id="{{ $participants[0]->status }}">
                    <a class="img" href="{{ url('participant/').'/'.$participants[0]->id }}"><img src="{{ Voyager::image($participants[0]->image) }}"></a>
                    <div class="fav-icon">
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
                    <div class="circle">
                        <a href="{{ url('users/').'/'.$participants[0]->user->id }}"><img src="{{ Voyager::image($participants[0]->user->avatar) }}"></a>
                        <h5>{{$participants[0]->name}}<br>1st Positon, {{$participants[0]->votes->count()}} Votes</h5>
                    </div>
                </div>
            </div>
            @endif
            @if(isset($participants[1]))
            <div class="half">
                @if(isset($participants[1]))
                <div class="box" data-part-id="{{ $participants[1]->status }}">
                    <a class="img" href="{{ url('participant/').'/'.$participants[1]->id }}"><img src="{{ Voyager::image($participants[1]->image) }}"></a>
                    <div class="fav-icon">
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
                    <div class="circle">
                        <a href="{{ url('users/').'/'.$participants[1]->user->id }}"><img src="{{ Voyager::image($participants[1]->user->avatar) }}"></a>
                        <h5>{{$participants[1]->name}}<br>2nd Positon, {{$participants[1]->votes->count()}} Votes</h5>
                    </div>
                </div>
                @endif
                @if(isset($participants[2]))

                
                <div class="box" data-part-id="{{ $participants[2]->status }}">
                    <a class="img" href="{{ url('participant/').'/'.$participants[2]->id }}"><img src="{{ Voyager::image($participants[2]->image) }}"></a>
                    <div class="fav-icon">
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
                    <div class="circle">
                        <a href="{{ url('users/').'/'.$participants[2]->user->id }}"><img src="{{ Voyager::image($participants[2]->user->avatar) }}"></a>
                        <h5>{{$participants[2]->name}}<br>3rd Positon, {{$participants[2]->votes->count()}} Votes</h5>
                    </div>
                </div>
                @endif
                @if(isset($participants[3]))
                <div class="box" data-part-id="{{ $participants[3]->status }}">
                    <a class="img" href="{{ url('participant/').'/'.$participants[3]->id }}"><img src="{{ Voyager::image($participants[3]->image) }}"></a>
                    <div class="fav-icon">
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
                    <div class="circle">
                        <a href="{{ url('users/').'/'.$participants[3]->user->id }}"><img src="{{ Voyager::image($participants[3]->user->avatar) }}"></a>
                        <h5>{{$participants[3]->name}}<br>4th Positon, {{$participants[3]->votes->count()}} Votes</h5>
                    </div>
                </div>
                @endif
                @if(isset($participants[4]))
                <div class="box" data-part-id="{{ $participants[4]->status }}">
                    <a class="img" href="{{ url('participant/').'/'.$participants[4]->id }}"><img src="{{ Voyager::image($participants[4]->image) }}"></a>
                    <div class="fav-icon">
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
                    <div class="circle">
                        <a href="{{ url('users/').'/'.$participants[4]->user->id }}"><img src="{{ Voyager::image($participants[4]->user->avatar) }}"></a>
                        <h5>{{$participants[4]->name}}<br>5th Positon, {{$participants[4]->votes->count()}} Votes</h5>
                    </div>
                </div>
                @endif
            </div>
            @endif
            @else
            <h3>No participant yet.</h3>   
            @endif
        </div>
    </section>
    @endif
@endforeach 

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
                data: {search: input,type:'country'},
                success: function(data) {
                    $('#list').empty();
                    $(data).each(function( index , d ) {

                        var url = '{{ url("country/") }}'+'/'+d.id;
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
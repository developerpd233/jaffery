@extends('layouts.app')
@section('meta_title', 'Winners')
@section('meta_description', 'Winners')
@section('page_title', 'Winners')
@section('content')

@section('css')
<style>
    
</style>
@endsection

<section class="photo-sec">
    <div class="container-fluid">
        <div class="full">
        @foreach($participants as $key => $participant)
                <div class="box">
                    <a class="img" href="{{ url('participant/').'/'.$participant->id }}"><img src="{{ Voyager::image($participant->image) }}"></a>
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
                    <div class="circle">
                        <a href="{{ url('users/').'/'.$participant->user->id }}"><img src="{{ Voyager::image($participant->user->avatar) }}"></a>
                        <h5>{{$participant->name}}<br>1st Positon, {{$participant->votes->count()}} Votes</h5>
                    </div>
                </div>
        @endforeach
        </div>
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
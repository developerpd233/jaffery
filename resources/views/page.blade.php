  
@extends('layouts.app')

@section('meta_title', $page->title)
@section('meta_description', $page->meta_description)
@section('page_title', $page->title)

@section('fb_title', $page->title)
@section('fb_url', Request::url())
@section('fb_type', 'article')
@section('fb_image', 'https://posetopost.com/public/img/Group3.jpg' )

{{-- @section('page_banner', $page->image, 1200, 211) --}}

@section('content')
{{-- @if (View::hasSection('page_banner')) style="background-image: url(@yield('page_banner'))" @endif --}}

{{-- *********** --}}

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v14.0&appId=439050034665686&autoLogAppEvents=1" nonce="wpRSUEU6"></script>

{{-- <div class="fb-share-button" data-href="https://posetopost.com/participant/40" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fposetopost.com%2Fparticipant%2F40&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div> --}}

{{-- <iframe src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fposetopost.com%2Fparticipant%2F40&layout=button&size=large&appId=439050034665686&width=77&height=28" width="77" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe> --}}

{{-- *********** --}}

<section class="trm-conditon">
    <div class="container-fluid">
        <div class="trms-txt">
            {!! $page->body !!}
        </div>
    </div>
</section>


@endsection
@extends('layouts.app.home')
@section('title', "{$episode->name} - {$film->name}")
@push('css')
  <link href="{{ asset('video.js/dist/video-js.css') }}" rel="stylesheet" />
  <script type="text/javascript" src="{{ asset('video.js/dist/ie8/videojs-ie8.min.js') }}"></script>
@endpush
@section('content')
  <!-- Player video and list film -->
  <div class="ui internally stackable grid">
    <div class="twelve wide column">
      <video id="player-video" class="video-js" controls preload="auto" width="640" height="264" poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
        <source src="MY_VIDEO.mp4" type="video/mp4" />
        <source src="MY_VIDEO.webm" type="video/webm" />
        <p class="vjs-no-js">
          To view this video please enable JavaScript, and consider upgrading to a web browser that
          <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
      </video>
    </div>
    <div class="four wide column">
      {{-- <div class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="first">First</a>
        <a class="item" data-tab="second">Second</a>
      </div>
      <div class="ui tab segment" data-tab="first">
        First
      </div>
      <div class="ui tab segment" data-tab="second">
        Second
      </div>
      <div class="ui tab segment" data-tab="third">
        Third
      </div> --}}
    </div>
  </div>
@endsection
@push('js')
  <script type="text/javascript" src="{{ asset('video.js/dist/video.min.js') }}"></script>
@endpush

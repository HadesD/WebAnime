@extends('layouts.app.home')
@section('title', "{$episode->name} - {$film->name}")
@push('css')
  <link href="{{ asset('video.js/dist/video-js.css') }}" rel="stylesheet" />
  <style type="text/css">
  #player-video {
    width: 100%;
    height: 460px;
  }
  @media (max-width: 767px) and (min-width: 640px) {
    #player-video {
      float: none;
      /*width: 640px;*/
      height: 360px;
    }
  }
  @media (max-width: 639px) and (min-width: 480px) {
    #player-video {
        /*width: 480px;*/
        height: 270px;
    }
  }
  @media (max-width: 639px) and (min-width: 375px) {
    #player-video {
        /*width: 375px;*/
        height: 211px;
    }
  }
  @media (max-width: 639px) {
    #player-video {
      /*width: 320px;*/
      height: 180px;
    }
  }
  </style>
  <script type="text/javascript" src="{{ asset('video.js/dist/ie8/videojs-ie8.min.js') }}"></script>
@endpush
@section('content')
  <!-- Player video and list film -->
  <div class="ui internally stackable grid">
    <div class="twelve wide column">
      <video id="player-video" class="video-js" controls preload="auto" poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
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
      <div class="ui very relaxed items">
        <div class="item">
          <div class="ui tiny image">
            <img src="/images/wireframe/image.png">
          </div>
          <div class="middle aligned content">
            <a class="header">12 Years a Slave</a>
          </div>
        </div>
        <div class="item">
          <div class="ui tiny image">
            <img src="/images/wireframe/image.png">
          </div>
          <div class="middle aligned content">
            <a class="header">My Neighbor Totoro</a>
          </div>
        </div>
        <div class="item">
          <div class="ui tiny image">
            <img src="/images/wireframe/image.png">
          </div>
          <div class="middle aligned content">
            <a class="header">Watchmen</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('js')
  <script type="text/javascript" src="{{ asset('video.js/dist/video.min.js') }}"></script>
@endpush

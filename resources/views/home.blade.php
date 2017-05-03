@extends('layouts.app.home')
@push('css')
  <link rel="stylesheet" href="{{ asset('libs/OwlCarousel2/dist/assets/owl.carousel.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('libs/OwlCarousel2/dist/assets/owl.theme.default.min.css') }}" />
  <style media="screen">
  #owl-demo{
    overflow: hidden;
  }
  #owl-demo .owl-item div{
    height: 260px;
  }
  #owl-demo .owl-item img{
    min-height: 100%;
  }
  #owl-demo i.icon{
    position: absolute;
    top: 50%;
    left:50%;
    margin-top: -28px;
    margin-left: -33px;
    cursor: pointer;
    opacity: 0;
  }
  #owl-demo .owl-item:hover i.icon{
    opacity: 1;
  }
  </style>
@endpush
@section('content')
  <div class="ui segment">
    <div id="owl-demo" class="owl-carousel owl-theme">
    @foreach ($carousel as $film)
      <a href="{{ $film->getRoute() }}">
        <div class="item">
            <img src="{{ $film->thumbnail }}" />
            <i class="video play huge icon"></i>
        </div>
      </a>
    @endforeach
  </div>
  </div>
  <h3 class="ui dividing red header">
    Dividing Header
  </h3>
  <div class="ui five special stackable cards">
    @foreach ($newestList as $newest)
      <div class="ui red card">
        <div class="blurring dimmable image">
          <div class="ui dimmer">
            <div class="content">
              <div class="center">
                <h4 class="ui inverted header">
                  @lang('watch.lastest'): {{ $newest->name }}
                </h4>
                <a class="ui inverted button" href="{{ $newest->getRoute() }}">
                  @lang('watch.watchnow')
                </a>
              </div>
            </div>
          </div>
          <img class="image" src="{{ $newest->film->thumbnail }}" />
        </div>
        <div class="content">
          <a class="header" href="{{ $newest->getRoute() }}">
            {{ $newest->film->name }}
          </a>
        </div>
        <div class="extra content">
          <i class="lightning icon"></i>
          @lang('watch.views'): {{ $newest->film->views }}
        </div>
      </div>
      {{-- <div class="ui red card">
        <a class="image" style="max-height: 185px;overflow: hidden;" href="{{ route('watch.episode', ['film_id' => $newest->film_id, 'episode_id' => $newest->id]) }}">
          <img src="{{ $newest->film->thumbnail }}" />
        </a>
        <div class="content">
          <a class="header" href="{{ route('watch.episode', ['film_id' => $newest->film_id, 'episode_id' => $newest->id]) }}">
            {{ $newest->film->name }}
          </a>
          <div class="meta">
            <a>{{ print_r($newest) }}</a>
          </div>
        </div>
      </div> --}}
    @endforeach
  </div>
@endsection
@push('js')
  <script type="text/javascript" src="{{ asset('libs/OwlCarousel2/dist/owl.carousel.min.js') }}"></script>
  <script type="text/javascript">
    $('.special.cards .image').dimmer({
      on: 'hover'
    });
    $(document).ready(function() {
      $("#owl-demo").owlCarousel({
        autoPlay : 3000,
        loop: true,
        margin:10,
        autoWidth:true,
      });
    });
  </script>
@endpush

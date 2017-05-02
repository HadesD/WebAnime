@extends('layouts.app.home')
@push('css')
  <link rel="stylesheet" href="{{ asset('libs/OwlCarousel2/dist/assets/owl.carousel.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('libs/OwlCarousel2/dist/assets/owl.theme.default.min.css') }}" />
  <style media="screen">
  #owl-demo .owl-item div{
    padding:5px;
    max-height: 500px;
  }
  #owl-demo .owl-item img{
    display: block;
    width: 100%;
    height: auto;
    margin-top: -35%;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
  }
  </style>
@endpush
@section('content')
  <div class="ui segment">
    <div id="owl-demo" class="owl-carousel owl-theme">
    @foreach ($carousel as $film)
      <div class="item">
        <a href="{{ $film->getRoute() }}">
          <img src="{{ $film->thumbnail }}" />
        </a>
      </div>
    @endforeach
  </div>
  </div>
  <h3 class="ui dividing red header">
    Dividing Header
  </h3>
  <div class="ui three special stackable cards">
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
        stopOnHover : true,
        //navigation:true,
        paginationSpeed : 1000,
        goToFirstSpeed : 2000,
        singleItem : true,
        autoHeight : true,
        transitionStyle:"fade"
      });
    });
  </script>
@endpush

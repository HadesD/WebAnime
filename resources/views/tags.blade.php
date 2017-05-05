@extends('layouts.app.home')
@section('content')
  <div class="ui dividing red header">
    @lang('home.tags'): {{ $tag->name }}
  </div>
  <div class="ui five special stackable link cards">
    @foreach ($films as $film)
      <a class="ui red card" href="{{ $film->getRoute() }}">
        @if (strlen($film->thumbnail) > 0)
          <div class="blurring dimmable image" href="{{ $film->getRoute() }}" style="max-height:150px;overflow:hidden;">
            <div class="ui dimmer">
              <div class="content">
                <div class="center">
                  <h4 class="ui inverted header">
                    @lang('watch.lastest'): {{ $film->name }}
                  </h4>
                  <div class="ui inverted button">
                    @lang('watch.watchnow')
                  </div>
                </div>
              </div>
            </div>
            <img class="image" src="{{ $film->thumbnail }}" />
          </div>
        @endif
        <div class="content">
          <div class="header">
            {{ $film->name }}
          </div>
        </div>
        <div class="extra content">
          <i class="lightning icon"></i>
          @lang('watch.views'): {{ $film->views }}
        </div>
      </a>
    @endforeach
  </div>
  {{ $films->links('vendor.pagination.semantic-ui') }}
@endsection

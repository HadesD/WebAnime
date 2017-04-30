@extends('layouts.app.home')

@section('content')
  <h3 class="ui dividing red header">
    Dividing Header
  </h3>
  <div class="ui four cards">
    @foreach ($newestList as $newest)
      <div class="ui red card">
        <a class="image" style="max-height: 185px;overflow: hidden;" href="{{ route('watch.episode', ['film_id' => $newest->film_id, 'episode_id' => $newest->id]) }}">
          <img src="{{ $newest->film->thumbnail }}">
        </a>
        <div class="content">
          <a class="header" href="{{ route('watch.episode', ['film_id' => $newest->film_id, 'episode_id' => $newest->id]) }}">
            {{ $newest->film->name }}
          </a>
          <div class="meta">
            <a>{{ print_r($newest) }}</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection

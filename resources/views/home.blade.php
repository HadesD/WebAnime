@extends('layouts.app.home')

@section('content')
  <h3 class="ui dividing red header">
    Dividing Header
  </h3>
  <div class="ui four cards">
    @foreach ($newestList as $newest)
      <div class="ui red card">
        <a class="image" style="max-height: 185px;overflow: hidden;" href="#">
          <img src="{{ $newest->film->thumbnail }}">
        </a>
        <div class="content">
          <a class="header" href="#">
            {{ $newest->film->name }}
          </a>
          <div class="meta">
            <a>{{ $newest->film_id }}</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection

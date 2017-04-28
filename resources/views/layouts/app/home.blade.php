@extends('layouts.app')
@section('wrapper')
  <div class="ui borderless icon big menu inverted fluid" style="border-radius:0;">
    <a class="item icon">
      <i class="content icon"></i>
    </a>
    <a class="item icon" href="{{ route('watch.index') }}">
      <i class="heartbeat icon"></i>
    </a>
    <div class="mobile hidden item">
      <div class="ui transparent inverted icon large input">
        <input placeholder="Search" type="text" />
        <i class="search icon"></i>
      </div>
    </div>
    <div class="right menu">
      <a class="mobile only item icon">
        <i class="search icon"></i>
      </a>
      <a class="item icon">
        <i class="alarm icon"></i>
      </a>
      <a class="item icon">
        <i class="user icon"></i>
      </a>
    </div>
  </div>
  <div class="ui container">
    @yield('content')
  </div>
  <div class="ui footer">

  </div>
@endsection

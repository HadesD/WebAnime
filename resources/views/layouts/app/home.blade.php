@extends('layouts.app')
@section('wrapper')
  <div class="ui borderless icon menu inverted fluid" style="border-radius:0;">
    <a class="item">
      <i class="content icon"></i>
    </a>
    <a class="item">Browse</a>
    <a class="item">Submit</a>
    <div class="item">
      <div class="ui transparent inverted icon large input">
        <input placeholder="Search" type="text" />
        <i class="search icon"></i>
      </div>
    </div>
    <div class="right menu">
      <a class="item">Sign Up</a>
      <a class="item">Help</a>
    </div>
  </div>
  <div class="ui container">
    @yield('content')
  </div>
  <div class="ui footer">
    
  </div>
@endsection
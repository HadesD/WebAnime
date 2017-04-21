@extends('layouts.app')
@section('wrapper')
  <div class="ui menu fluid" style="border-radius:0;">
    <a class="item">Browse</a>
    <a class="item">Submit</a>
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
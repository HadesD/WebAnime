@extends('layouts.app.home')
@section('title', "{$episode->name} - {$film->name}")
@section('content')
  <!-- Player video and list film -->
  <div class="ui internally stackable grid">
    <div class="twelve wide column">
      sdfsdf
    </div>
    <div class="four wide column">
      <div class="ui pointing secondary stackable menu">
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
      </div>
    </div>
  </div>
@endsection
@extends('layouts.app.home')
@section('content')
  @foreach ($results as $value)

  @endforeach
  {{ $results->links('vendor.pagination.semantic-ui') }}
@endsection

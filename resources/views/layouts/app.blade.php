<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>@yield('title')</title>

  <!-- Styles -->
  <link href="{{ asset('Semantic-UI/dist/semantic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  @stack('css')
  <link href="{{ asset('css/app.hack.css') }}" rel="stylesheet" />
  <!-- Scripts -->
  <script type="text/javascript">
  window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
  </script>
</head>
<body>
  @yield('wrapper')
  <!-- Scripts -->
  <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('Semantic-UI/dist/semantic.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
  @stack('js')
  <script type="text/javascript" src="{{ asset('js/app.hack.js') }}"></script>
</body>
</html>

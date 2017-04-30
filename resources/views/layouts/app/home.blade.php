@extends('layouts.app')
@section('wrapper')
  <div class="ui borderless icon big menu fluid" style="border-radius:0;">
    <a class="item icon">
      <i class="content icon"></i>
    </a>
    <a class="item icon" href="{{ route('watch.index') }}">
      <i class="heartbeat red icon"></i>
    </a>
    <div class="mobile hidden item">
      <div id="find-film" class="ui search">
        <div class="ui transparent icon input">
          <input class="prompt" type="text" placeholder="@lang('navbar.search.type')" />
          <i class="search icon"></i>
        </div>
        <div class="ui left aligned container results"></div>
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
@push('js')
  <script type="text/javascript">
    $('#find-film').search({
      apiSettings: {
        url: '{{ route('api.search.film', ['query'=>'']) }}/{query}',
        onResponse: function(data) {
          var
            response = {
              results : []
            }
          ;
          // translate GitHub API response to work with search
          $.each(data, function(index, item) {
            var maxResults = 8;
            if(index >= maxResults) {
              return false;
            }
            // add result to category
            response.results.push({
              title       : item.name,
              description : '@lang('watch.views') '+item.views,
              url         : '{{ route('watch.index') }}/'+item.id+'/'+item.slug,
              image       : item.thumbnail,
            });
          });
          return response;
        },
      },
      minCharacters : 2
    });
  </script>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=273501966143679";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
@endpush

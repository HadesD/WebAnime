@extends('layouts.app')
@section('wrapper')
  <div class="ui borderless icon menu fluid" style="border-radius:0;border:0;border-top:2px solid #0089ff;">
    <a class="item icon">
      <i class="content large icon"></i>
    </a>
    <a class="item icon" href="{{ route('home') }}">
      <i class="home large icon"></i>
    </a>
    <a class="item icon" href="{{ route('watch.index') }}">
      <i class="heartbeat red large icon"></i>
    </a>
    <div class="mobile hidden item">
      <div id="find-film" class="ui search">
        <div class="ui transparent icon input">
          <input class="prompt" type="text" placeholder="@lang('navbar.search.type')" />
          <i class="search large icon"></i>
        </div>
        <div class="ui left aligned container results"></div>
      </div>
    </div>
    <div class="right menu">
      <a class="mobile only item icon">
        <i class="search large icon"></i>
      </a>
      <a class="item icon">
        <i class="alarm large icon"></i>
      </a>
      <a class="item icon">
        <i class="user large icon"></i>
      </a>
    </div>
  </div>
  <div class="ui container" style="min-height:100%;margin-bottom:40px;">
    @yield('content')
  </div>
  <div class="ui inverted vertical footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">About</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Sitemap</a>
            <a href="#" class="item">Contact Us</a>
            <a href="#" class="item">Religious Ceremonies</a>
            <a href="#" class="item">Gazebo Plans</a>
          </div>
        </div>
        <div class="three wide column">
          <h4 class="ui inverted header">Services</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Banana Pre-Order</a>
            <a href="#" class="item">DNA FAQ</a>
            <a href="#" class="item">How To Access</a>
            <a href="#" class="item">Favorite X-Men</a>
          </div>
        </div>
        <div class="seven wide column">
          <h4 class="ui inverted header">Footer Header</h4>
          <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
        </div>
      </div>
    </div>
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

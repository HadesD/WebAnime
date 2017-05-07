@extends('layouts.app')
@section('wrapper')
  <div class="ui borderless icon menu fluid" style="border-radius:0;border:0;border-top:2px solid #f00;">
    <a class="item icon">
      <i class="content large icon"></i>
    </a>
    <a class="item icon" href="{{ route('home') }}">
      <i class="home large icon"></i>
    </a>
    <a class="item icon" href="{{ route('watch.index') }}">
      <i class="heartbeat red large icon"></i>
    </a>
    <a class="item icon" id="navbar-close-search" style="display:none;">
      <i class="remove large icon"></i>
    </a>
    <div class="mobile hidden item">
      <form id="find-film" class="ui search" method="POST" action="{{ route('search.film') }}">
        <div class="ui transparent icon input">
          {{ csrf_field() }}
          <input class="prompt" type="text" name="query" value="@if(Route::currentRouteName()==='search.film'){{ (Route::current()->parameters()['query']) }}@endif" placeholder="@lang('navbar.search.type')" />
          <i class="search large icon"></i>
        </div>
        <div class="ui left aligned container results"></div>
      </form>
    </div>
    <div class="right menu">
      <a class="mobile only item icon" id="navbar-open-search">
        <i class="search large icon"></i>
      </a>
      @if (Auth::check())
        <div class="ui icon top right pointing inline dropdown item">
          <i class="alarm large icon"></i>
          <div class="menu">
            <div class="ui feed">
              <div class="item">
                <a class="event">
                  <div class="label">
                    <img src="/images/avatar/small/elliot.jpg">
                  </div>
                  <div class="content">
                    <div class="summary">
                      <a class="user">
                        Elliot Fu
                      </a> added you as a friend
                      <div class="date">
                        1 Hour Ago
                      </div>
                    </div>
                    <div class="meta">
                      <a class="like">
                        <i class="like icon"></i> 4 Likes
                      </a>
                    </div>
                  </div>
                </a>
              </div>
              <div class="item">
                <a class="event">
                  <div class="label">
                    <img src="/images/avatar/small/elliot.jpg">
                  </div>
                  <div class="content">
                    <div class="summary">
                      <a class="user">
                        Elliot Fu
                      </a> added you as a friend
                      <div class="date">
                        1 Hour Ago
                      </div>
                    </div>
                    <div class="meta">
                      <a class="like">
                        <i class="like icon"></i> 4 Likes
                      </a>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
        <a class="item icon">
          <i class="user large icon"></i>
        </a>
      @else
        <a class="item" href="{{ route('login') }}" onclick="$('#loginModal').modal('show');return false;">
          <i class="user large icon"></i>
          <span class="mobile hidden">
            @lang('app.login')
          </span>
        </a>
        <form class="ui modal" id="loginModal" method="POST" action="">
          <div class="header">
            @lang('app.login')
          </div>
          <div class="content">
            <p></p>
          </div>
          <div class="actions">
            <div class="ui approve blue button">@lang('app.login')</div>
            <div class="ui cancel red button">@lang('app.cancel')</div>
          </div>
        </form>
        <a class="item" href="{{ route('register') }}">
          <i class="add user large icon"></i>
          <span class="mobile hidden">
            @lang('app.register')
          </span>
        </a>
      @endif
    </div>
  </div>
  @yield('under.navbar')
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
    // Search form
    $('#find-film').search({
      apiSettings: {
        url: '{{ route('api.search.film', ['query'=>'']) }}/{query}',
        onResponse: function(data) {
          console.log();
          var response = {
            results : [],
            action : {
              url: '{{ route('search.film', ['query'=>'']) }}/'+$("#find-film :input[name=query]").val(),
              text: 'View all results',
            },
          };
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
    // Search on Responsive
    $(document).on('click', '#navbar-open-search', function(event) {
      $(this).closest('.ui.menu').find('a, .ui.dropdown').not('#navbar-close-search').fadeOut('fast', function(){
        $('#navbar-close-search').fadeIn('fast');
        $('#find-film').closest('.item').removeClass('hidden').slideDown();
      });
    }).on('click', '#navbar-close-search', function(event) {
      $('#find-film').closest('.item').addClass('hidden').hide();
      $(this).fadeOut('fast', function(){
        $('#navbar-close-search').closest('.ui.menu').find('a,.ui.dropdown').not('#navbar-close-search').fadeIn('fast');
      });
    });
    // Login Modal

  </script>
@endpush

@extends('layouts.app.home')
@if (isset($episode))
  @section('title', "{$episode->name} - {$film->name}")
@else
  @section('title', "{$film->name}")
@endif
@push('css')
  <link href="{{ asset('libs/video.js/dist/video-js.css') }}" rel="stylesheet" />
  <link href="{{ asset('libs/video.js/dist/skins/videojs-flat-skin.css') }}" rel="stylesheet" />
  <link href="{{ asset('libs/video.js/dist/skins/dark-hades.css') }}" rel="stylesheet" />
  <style type="text/css">
  #film-wrapper > .row > .column {
    padding-left:0;
    padding-right:0;
  }
  #episode-list {
    border-radius:0;
    width:auto;
    position:relative;
    overflow: hidden;
  }
  #player-video * {
    outline: 0;
  }
  #wrapper-video,
  #player-video,
  #episode-list {
    width: 100%;
    height: 460px;
  }
  @media (max-width: 767px) and (min-width: 640px) {
    #wrapper-video,
    #player-video,
    #episode-list {
      height: 360px;
    }
  }
  @media (max-width: 639px) and (min-width: 480px) {
    #wrapper-video,
    #player-video,
    #episode-list {
      height: 270px;
    }
  }
  @media (max-width: 639px) and (min-width: 375px) {
    #wrapper-video,
    #player-video,
    #episode-list {
      height: 211px;
    }
  }
  @media (max-width: 639px) {
    #wrapper-video,
    #player-video,
    #episode-list {
      height: 180px;
    }
  }
  </style>
@endpush
@section('content')
  <div id="film-wrapper" class="ui equal width internally stackable grid">
    @if (isset($episode))
      <div class="row">
        <div class="column">
          <div id="wrapper-video" class="ui loading basic segment" style="padding:0;border-radius:0;"></div>
        </div>
        <div class="four wide column">
          <div id="episode-list" class="ui vertical small menu">
            <template v-for="episode, index in episodes">
              <a class="item" :href="'{{ route('watch.episode', ['film_id' => $film->id, 'episode_id' => '']) }}/'+episode.id+'/{{ $film->slug }}/'+episode.slug" onclick="return false;" :class="{'active':episode.id==thisEpisode.id}" v-on:click="playEpisode(index)" v-bind:data-episodeid="episode.id">
                <div class="ui equal width grid">
                  <div class="row">
                    <div class="three wide column" style="padding-right:0;" v-if="thisEpisode.thumbnail">
                      <img :src="thisEpisode.thumbnail" class="ui rounded image" />
                    </div>
                    <div class="column" style="padding-right:5px;">
                      <div class="ui container" style="margin-bottom: 0px;">
                        @{{ episode.name }}
                      </div>
                      <small>@lang('watch.views'): @{{ number_format(episode.views) }}</small>
                    </div>
                  </div>
                </div>
              </a>
            </template>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="column">
        <h1 class="ui red header">
          <i class="film black icon"></i>
          {{ $film->name }}
        </h1>
      </div>
      <div class="five wide column">
        <div class="ui right aligned container">
          <div class="ui tiny teal statistic">
            <div class="value">
              <i class="lightning icon"></i>
              {{ $film->views }}
            </div>
            <div class="label">
              @lang('watch.views')
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row ui segment" style="padding:10px;">
      <div class="column">
        <div class="ui huge relaxed divided list">
          <div class="item">
            <i class="users icon"></i>
            <div class="content">
              <button type="button" class="ui circular icon blue tiny button" data-tooltip="@lang('watch.follow')">
                <i class="alarm icon"></i>
              </button>
            </div>
          </div>
          <div class="item">
            <i class="share alternate icon"></i>
            <div class="content">
              <div class="fb-like" data-href="{{ $film->getRoute() }}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
            </div>
          </div>
          @if (count($film->tags) > 0)
            <div class="item">
              <i class="tags icon"></i>
              <div class="content">
                @foreach ($film->tags as $tag)
                  <a class="ui tag teal label" href="{{ route('tags', ['tag_id' => $tag->id, 'tag_slug' => $tag->slug]) }}">
                    {{ $tag->name }}
                  </a>
                @endforeach
              </div>
            </div>
          @endif
        </div>
        @if (strlen($film->description) > 0)
          <div class="ui container">
            <i class="book icon"></i>
            {{ $film->description }}
          </div>
        @endif
        <div class="ui pointing secondary menu">
          <a class="item active" data-tab="first">
            <i class="facebook icon"></i>
            @lang('watch.comments')
          </a>
        </div>
        <div class="ui tab segment active" data-tab="first">
          <div class="fb-comments" data-width="100%" data-href="{{ $film->getRoute() }}" data-numposts="5"></div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('js')
  @if (isset($episode))
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{ asset('libs/video.js/dist/ie8/videojs-ie8.min.js') }}"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ asset('libs/video.js/dist/video.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/video.js/easybits-videojs-thumbnails/easybits-helper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/video.js/easybits-videojs-thumbnails/easybits-multistreaming.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/video.js/easybits-videojs-thumbnails/jdataview.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/video.js/easybits-videojs-thumbnails/easybits-mp4.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/video.js/easybits-videojs-thumbnails/easybits-videojs-thumbnails.js') }}"></script>
    <script type="text/javascript">
    // Autoload
    var videoPlayer;
    var isAjaxDoing = false;

    // Ajax
    var thisEpisode = {
      id: {{ $episode->id }},
      // src: 'http://download.blender.org/peach/bigbuckbunny_movies/BigBuckBunny_320x180.mp4',
      // thumbnail: '{{ $film->thumbnail }}',
      type: '',
    };
    var episodes = [];

    // Vue.js Support
    var playerVideo = $("#wrapper-video");
    var episodeList = new Vue({
      el: '#episode-list',
      mounted: function() {
        $.get("{{ route('api.watch.film', ['film_id' => $film->id]) }}", function(data) {
          var idx;
          $.each( data, function( i, v ) {
            episodes.push(v);
            if (v.id === {{ $episode->id }}) {
              idx = i;
            }
          });
          var epListWrapper = $(episodeList.$el);
          setTimeout(function() {
            playEpisode(idx);
            epListWrapper.perfectScrollbar();
            // scrollTop
            var active = epListWrapper.find('.item.active');
            epListWrapper.scrollTop(active.offset().top - active.offsetParent().offset().top);
          }, 0);
        });
      },
      component: {
        'template': this,
      },
      data: {
        thisEpisode: thisEpisode,
        episodes: episodes,
      },
    });
    function playEpisode(i)
    {
      if ((thisEpisode.id === episodes[i].id) && (isAjaxDoing === true))
      {
        return;
      }

      playerVideo.addClass('loading');

      episodes[i].views++;
      thisEpisode.id = episodes[i].id;

      var epListWrapper = $(episodeList.$el);
      var target = epListWrapper.find('[data-episodeid='+episodes[i].id+']');

      // Current page Attr change
      window.history.pushState(this.data, document.title, target.attr('href'));
      document.title = target.find('h5').text();

      playerVideo.find('.video-js').addClass('vjs-waiting');

      // Start getlink
      isAjaxDoing = true;
      $.get("{{ route('api.watch.episode', ['film_id' => $film->id, 'episode_id' => '']) }}/"+thisEpisode.id, function(data) {
        playerVideo.removeClass('loading');
        if (typeof vjsPlayer == "undefined")
        {
          videoPlayer = $("<video />", {
            "id": "player-video",
            "class": "video-js vjs-big-play-centered vjs-dark-hades",
            "controls": "",
            "autoplay": "true",
            "preload": "auto",
            "poster": "{{ $film->thumbnail }}",
          });
          playerVideo.html(videoPlayer);
        }
        isAjaxDoing = false;
        if (data.s === false)
        {
          return;
        }
        // Update player
        thisEpisode.src = data['srcs'][0]['src'];
        thisEpisode.type = 'video/mp4';
        vjsPlayer = videojs(playerVideo.find("video").attr("id"));
        vjsPlayer.src({ "type":thisEpisode.type, "src":thisEpisode.src });
        $.each(playerVideo.find("button"), function(index, el) {
          if ($(el).attr('data-tooltip') !== undefined)
          {
            return;
          }
          $(el).attr("data-tooltip", $(el).attr("title"));
          $(el).attr('data-inverted', '');
          $(el).removeAttr("title");
        });
      });
    }
    window.onpopstate = function(event) {
      //episodeList.playEpisode(event,10);
      //console.log(JSON.stringify(event.state));
      //alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
    };
    function number_format(nStr)
    {
    	nStr += '';
    	x = nStr.split('.');
    	x1 = x[0];
    	x2 = x.length > 1 ? '.' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + ',' + '$2');
    	}
    	return x1 + x2;
    }
    var easybits_FplScrubber = {
			swfgeneratorurl:"{{ asset('libs/video.js/easybits-videojs-thumbnails/easybits-topng.swf') }}",
      loaders:["{{ asset('libs/video.js/easybits-videojs-thumbnails/easybits-httpstreaming.html') }}"],
			width:160,
			height:90
		};
    </script>
  @endif
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=273501966143679";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
@endpush

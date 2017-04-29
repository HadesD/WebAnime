  @extends('layouts.app.home')
  @section('title', "{$episode->name} - {$film->name}")
  @push('css')
    <link href="{{ asset('libs/video.js/dist/video-js.css') }}" rel="stylesheet" />
    <link href="{{ asset('libs/video.js/dist/skins/videojs-flat-skin.css') }}" rel="stylesheet" />
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
      #player-video,
      #episode-list {
        width: 100%;
        height: 460px;
      }
      @media (max-width: 767px) and (min-width: 640px) {
        #player-video,
        #episode-list {
          height: 360px;
        }
      }
      @media (max-width: 639px) and (min-width: 480px) {
        #player-video,
        #episode-list {
          height: 270px;
        }
      }
      @media (max-width: 639px) and (min-width: 375px) {
        #player-video,
        #episode-list {
          height: 211px;
        }
      }
      @media (max-width: 639px) {
        #player-video,
        #episode-list {
          height: 180px;
        }
      }
    </style>
  @endpush
  @section('content')
    <!-- Player video and list film -->
    <div id="film-wrapper" class="ui equal width internally stackable grid">
      <div class="row">
        <div class="column">
          <video id="player-video" class="video-js vjs-dark-hades" controls autoplay preload="auto" v-bind:poster="thisEpisode.thumbnail" data-setup="{'playbackRates': [1, 1.5, 2]}" v-bind:src="thisEpisode.src"></video>
        </div>
        <div class="four wide column">
          <!-- <div class="ui pointing secondary stackable menu">
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
          </div> -->
          <div id="episode-list" class="ui inverted vertical small menu">
            <template v-for="episode, index in episodes">
              <a class="item" :href="'{{ route('watch.episode', ['film_id' => $film->id, 'episode_id' => '']) }}/'+episode.id+'/{{ $film->slug }}/'+episode.slug" onclick="return false;" :class="{'active':episode.id==thisEpisode.id}" v-on:click="playEpisode($event, index)" v-bind:data-episodeid="episode.id">
                <div class="ui equal width grid">
                  <div class="row">
                    <div class="three wide column" style="padding-right:0;" v-if="thisEpisode.thumbnail">
                      <img :src="thisEpisode.thumbnail" class="ui rounded image" />
                    </div>
                    <div class="column" style="padding-right:5px;">
                      <h5 class="ui inverted header" style="margin-bottom: 0px;">
                        @{{ episode.name }}
                      </h5>
                      <small>@lang('watch.views'): @{{ episode.views }}</small>
                    </div>
                  </div>
                </div>
              </a>
            </template>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="column">
          <h1 class="ui header">
            {{ $film->name }}
          </h1>
          @foreach ($film->tags() as $key => $tag)
            <a class="ui tag label" href="{{ route('tags', ['tag_id' => $tag->id, 'tag_slug' => $tag->slug]) }}">
              {{ $tag->name }}
            </a>
          @endforeach
        </div>
        <div class="five wide column">
          <div class="ui right aligned container">
            <div class="ui small statistic">
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
      <div class="row">
        <div class="column">
          {{ $film->description }}
        </div>
      </div>
    </div>
  @endsection
  @push('js')
    <!--[if lt IE 9]>
      <script type="text/javascript" src="{{ asset('libs/video.js/dist/ie8/videojs-ie8.min.js') }}"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ asset('libs/video.js/dist/video.min.js') }}"></script>
    <script type="text/javascript">
      // Autoload
      var vjsPlayer;
      var isAjaxDoing = false;

      // Ajax
      var thisEpisode = {
        id: {{ $episode->id }},
        // src: 'http://download.blender.org/peach/bigbuckbunny_movies/BigBuckBunny_320x180.mp4',
        thumbnail: '{{ $film->thumbnail }}',
        type: '',
      };
      var episodes = [];

      // Vue.js Support
      var playerVideo = new Vue({
        el: '#player-video',
        data: {
          thisEpisode: thisEpisode,
        },
      });
      $(function() {
        vjsPlayer = videojs(playerVideo.$el.id);
      });
      var episodeList = new Vue({
        el: '#episode-list',
        mounted: function() {
          $.get("{{ route('api.watch.film', ['film_id' => $film->id]) }}", function(data) {
            $.each( data, function( i, v ) {
              episodes.push(v);
            });
            var epListWrapper = $(episodeList.$el);
            setTimeout(function() {
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
        methods: {
          playEpisode: function(event, i) {
            if ((thisEpisode.id === episodes[i].id) && (isAjaxDoing === true))
            {
              return;
            }
            episodes[i].views++;
            thisEpisode.id = episodes[i].id;
            var target = $(event.target).closest('a');

            // Current page Attr change
            window.history.pushState(this.data, document.title, target.attr('href'));
            document.title = target.find('h5').text();

            $(playerVideo.$el).closest('.video-js').addClass('vjs-waiting');

            // Start getlink
            isAjaxDoing = true;
            $.get("{{ route('api.watch.episode', ['film_id' => $film->id, 'episode_id' => '']) }}/"+thisEpisode.id, function(data) {
              isAjaxDoing = false;
              if (data.s === false)
              {
                return;
              }
              // Update player
              thisEpisode.src = data['srcs'][0]['src'];
              $('#'+playerVideo.$el.id).attr('src', thisEpisode.src);
              thisEpisode.type = 'video/mp4';
              //vjsPlayer.play();
            });
          },
        },
      });
      window.onpopstate = function(event) {
        //episodeList.playEpisode(event,10);
        console.log(JSON.stringify(event.state));
        //alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
      };
    </script>
  @endpush

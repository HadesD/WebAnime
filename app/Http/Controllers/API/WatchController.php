<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Film;
use App\Episode;

class WatchController extends Controller
{
  public function getLink($url)
  {
    $namespace = 'App\Http\Controllers\API\Watch';

    $getLinkController = $namespace.'\GetLinkController';

    if (filter_var($url, FILTER_VALIDATE_URL))
    {
      $class = $namespace.'\GetLink\\'.studly_case_domain(parse_url($url)['host']);

      if (class_exists($class))
      {
        $getLinkController = $class;
      }
    }

    $getLinkController = new $getLinkController;
    $getLinkController->setUrl($url);

    return $getLinkController->getResults();
  }

  public function watchEpisode($film_id, $episode_id)
  {
    $episode = Episode::find($episode_id);
    $episode->views += 1;
    $rtn = $this->getLink($episode->source);
    $episode->save();

    return $rtn;
  }

  public function watchFilm($film_id)
  {
    $film = Film::find($film_id);

    return $film->episodes()->get()->sortBy(function($episode) {
      return $episode->ordinal;
    });
  }
}

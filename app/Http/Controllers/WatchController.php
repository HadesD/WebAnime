<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use App\Episode;
use App\Http\Controllers\Controller;

class WatchController extends Controller
{
  public function index()
  {
    # code...
  }

  public function watch($film_id)
  {
    return 'film';
  }

  public function watchEpisode($film_id, $episode_id)
  {
    return 'ep';
  }

  public function getLink($url)
  {
    $namespace = 'App\Http\Controllers\Watch';

    $getLinkController = $namespace.'\GetLinkController';

    $url = base64_decode($url);

    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $class = $namespace.'\GetLink\\'.studly_case(preg_replace('/[^A-Za-z0-9\-]/', '_', parse_url($url)['host']));

      if (class_exists($class)) {
        $getLinkController = $class;
      }
    }

    $getLinkController = new $getLinkController;
    $getLinkController->setUrl($url);

    return $getLinkController->getResults();
  }
}

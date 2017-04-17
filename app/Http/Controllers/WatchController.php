<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use App\Episode;

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
    $getLinkController = new Watch\GetLinkController;
    $getLinkController->setUrl($url);
    
    return $getLinkController->getResults();
  }
}

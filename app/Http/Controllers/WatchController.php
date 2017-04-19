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
    $film = Film::first();
    
    if (empty($film->id) === true)
    {
      return 404;
    }
    
    return $this->watch($film->id);
  }

  public function watch($film_id)
  {
    $episode = Episode::where('film_id', $film_id)->first();
    
    if (empty($episode->id) === true)
    {
      return 40555;
    }
    
    return $this->watchEpisode($film_id, $episode->id);
  }

  public function watchEpisode($film_id, $episode_id)
  {
    return view('watch');
  }
}

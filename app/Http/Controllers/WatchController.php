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
    
    if (count($film) <= 0)
    {
      return 1;
    }
    
    return redirect()->route('watch.film', [
      'film_id' => $film->id,
    ]);
  }

  public function watchFilm($film_id)
  {
    $episode = Episode::where('film_id', $film_id)->first();
    if (count($episode) <= 0)
    {
      return 2;
    }
    
    $film = Film::where('id', $film_id)->first();
    if (count($film) <= 0)
    {
      return 2.5;
    }
    
    return redirect()->route('watch.episode', [
      'film_id'      => $film_id,
      'episode_id'   => $episode->id,
      'film_slug'    => str_slug($film->name),
      'episode_slug' => str_slug($episode->name),
    ]);
  }

  public function watchEpisode($film_id, $episode_id)
  {
    $film = Film::where('id', $film_id)->first();
    if (count($film) <= 0)
    {
      return 3;
    }
    
    $episode = Episode::where('film_id', $film_id)->first();
    if (count($episode) <= 0)
    {
      return 4;
    }
    
    return view('watch', [
      'film' => $film,
      'episode' => $episode,
    ]);
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use App\FilmEpisode;
use App\Http\Controllers\Controller;

class WatchController extends Controller
{
  public function index()
  {
    $date = intval(date('Ymd'));

    $count = Film::count();

    $count = $count ? $count : 1;

    $choose = $date % $count;

    $film = Film::find($choose);

    if (count($film) === 0)
    {
      $film = Film::first();
    }

    return redirect()->route('watch.film', [
      'film_id' => $film->id,
    ]);
  }

  public function watchFilm($film_id)
  {
    $film = Film::find($film_id);
    if (count($film) === 0)
    {
      return 404;
    }

    $episode = $film->episodes()->first();

    if (count($episode) === 0)
    {
      return view('watch', [
        'film'    => $film,
      ]);
    }

    return redirect()->route('watch.episode', [
      'film_id'      => $episode->film_id,
      'episode_id'   => $episode->id,
      'film_slug'    => $episode->film->slug,
      'episode_slug' => $episode->slug,
    ]);
  }

  public function watchEpisode($film_id, $episode_id)
  {
    $film = Film::find($film_id);
    if (count($film) === 0)
    {
      return 404;
    }

    $episode = $film->episodes()->find($episode_id);
    if (count($episode) === 0)
    {
      return 404;
    }

    return view('watch', [
      'film'    => $film,
      'episode' => $episode,
    ]);
  }
}

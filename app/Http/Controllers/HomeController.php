<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use App\FilmEpisode;
use DB;

class HomeController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    // $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $carousel = Film::whereNotNull('thumbnail')->inRandomOrder()->limit(8)->get();

    $episodeNewest = FilmEpisode::
      //distinct('film_id')
      //select(['id', 'film_id'])
      //->groupBy('film_id')

      orderBy('created_at', 'DESC')
      ->limit(10)
      ->get();

    $filmNewest = Film::whereNotNull('thumbnail')
      ->orderBy('created_at', 'DESC')
      ->limit(10)
      ->get();

    return view('home', [
      'carousel' => $carousel,
      'episodeNewest' => $episodeNewest,
      'filmNewest' => $filmNewest,
    ]);
  }
}

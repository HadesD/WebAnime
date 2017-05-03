<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use App\FilmEpisode;

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

    $newestList = FilmEpisode::
      //distinct('film_id')
      //groupBy('film_id')
      orderBy('created_at', 'DESC')
      ->limit(10)
      ->get();

    return view('home', [
      'carousel' => $carousel,
      'newestList' => $newestList,
    ]);
  }
}

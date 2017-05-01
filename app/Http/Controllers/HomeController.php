<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Film;
use App\Episode;

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
    $newestList = Episode::orderBy('updated_at', 'DESC')
      ->limit(9)
      //->makeVisible('name')
      ->get();

    return view('home', [
      'carousel' => $carousel,
      'newestList' => $newestList,
    ]);
  }
}

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
    $newestList = Episode::selectRaw('DISTINCT(film_id)')
      //->groupBy('film_id')
      //->orderBy('updated_at', 'DESC')
      ->limit(8)
      ->get();

    return view('home', [
      'newestList' => $newestList,
    ]);
  }
}

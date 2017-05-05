<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
  public function postFilms(Request $request)
  {
    return redirect()->route('search.film', [
      'query' => $request->input('query'),
    ]);
  }
  public function films($query)
  {
    $rs = \App\Film::where('name', 'LIKE', "%{$query}%")->orderBy('name', 'DECS')->paginate(5);

    return view('search', [
      'results' => $rs,
    ]);
  }
}

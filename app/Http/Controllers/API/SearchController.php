<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
  public function film($query)
  {
    return \App\Film::where('name', 'LIKE', "%{$query}%")->limit(5)->get()->makeHidden(['description', 'tags'])->toArray();
  }
}

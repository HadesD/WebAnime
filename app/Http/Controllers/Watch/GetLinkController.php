<?php

namespace App\Http\Controllers\Watch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetLinkController extends Controller
{
    public function index($url)
    {
      
      return $url;
    }
}

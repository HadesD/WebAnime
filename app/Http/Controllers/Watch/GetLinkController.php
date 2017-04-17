<?php

namespace App\Http\Controllers\Watch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetLinkController extends Controller
{
  public $url;
  
  public function setUrl($url)
  {
    $this->url = $url;
  }
  
  public function getResults()
  {
    return 1;
  }
}

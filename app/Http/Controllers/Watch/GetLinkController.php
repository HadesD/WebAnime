<?php

namespace App\Http\Controllers\Watch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetLinkController extends Controller
{
  public $url;
  public $results;
  
  public function __construct()
  {
    $this->results = [
      'm' => null, // Message
      's' => false, // Success status
    ];
  }
  
  public function canGetLink()
  {
    return true;
  }
  
  public function setUrl($url)
  {
    $this->url = $url;
  }
  
  public function getResults()
  {
    
    if($this->canGetLink())
    {
      $this->results['s'] = true;
    }
    else
    {
      
    }
    
    return $this->results;;
  }
}

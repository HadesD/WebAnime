<?php

namespace App\Http\Controllers\Watch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetLinkController extends Controller
{
  protected $url;
  protected $results;

  public function __construct()
  {
    $this->results = [
      'm'    => null, // Messageage
      's'    => false, // Success status
      'srcs' => [],
    ];
  }

  protected function canGetLink()
  {

    return true;
  }

  public function setUrl($url)
  {
    $this->url = $url;
  }

  public function getResults()
  {

    if($this->canGetLink() === true)
    {
      unset($this->results['m']);
      $this->results['s'] = true;
    }
    else
    {
      unset($this->results['srcs']);
    }

    return $this->results;
  }
}

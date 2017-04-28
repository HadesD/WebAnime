<?php

namespace App\Http\Controllers\API\Watch;

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
      'srcs' => [
        /*[
          'quality' => '360p',
          'src' => 'link',
          'type' => 'video/mp4',
        ]*/
      ],
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
      if (empty($this->results['srcs']) === false)
      {
        $this->results['s'] = true;
      }
    }

    if ($this->results['s'] === false)
    {
      unset($this->results['srcs']);
    }
    else
    {
      unset($this->results['m']);
    }

    return $this->results;
  }
}

<?php

namespace App\Http\Controllers\Watch\GetLink;

use Illuminate\Http\Request;
use App\Http\Controllers\Watch\GetLinkController;
//use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class VuigheNet extends GetLinkController
{
  protected function canGetLink()
  {
    parent::canGetLink();
    // http://vuighe.net/shigatsu-wa-kimi-no-uso/tap-14 => aHR0cDovL3Z1aWdoZS5uZXQvc2hpZ2F0c3Utd2Eta2ltaS1uby11c28vdGFwLTE0
    $parseUrl = parse_url($this->url);
    $client = new Client([
      'base_uri' => 'http://'.$parseUrl['host'],
      'http_errors' => false,
      'allow_redirects' => false,
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest',
        'Referer'          => $parseUrl['host'],
      ],
    ]);

    $res = $client->request('GET', $parseUrl["path"], []);

    if ($res->getStatusCode() !== 200)
    {
      return false;
    }

    preg_match('/id="filmPage".*?data-id="(\d+)"/msi', $res->getBody(), $film_id);
    if (isset($film_id[1]) !== true)
    {
      return false;
    }

    preg_match('/id="filmPage".*?data-episode-id="(\d+)"/msi', $res->getBody(), $episode_id);
    if (isset($episode_id[1]) !== true)
    {
      return false;
    }

    $getSrcs = $client->request('GET', '/api/v2/films/'.$film_id[1].'/episodes/'.$episode_id[1], []);
    if ($getSrcs->getStatusCode() !== 200)
    {
      return false;
    }

    $json = json_decode($getSrcs->getBody(), true);

    if (isset($json['sources']) !== true)
    {
      return false;
    }

    if (isset($json['sources']['data']) !== true)
    {
      return false;
    }

    //print_r($json['sources']['data']);

    $this->results['srcs'] = $json['sources']['data'];

    return true;
  }
}

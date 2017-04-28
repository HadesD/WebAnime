<?php

namespace App\Http\Controllers\API\Watch\GetLink;

use Illuminate\Http\Request;
use App\Http\Controllers\API\Watch\GetLinkController;
use Symfony\Component\DomCrawler\Crawler;
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
    $dom = new Crawler((string)$res->getBody());
    $filmPage = $dom->filter('#filmPage');
    if (count($filmPage) === 0) {
      return false;
    }

    $film_id = $filmPage->attr('data-id');
    if (count($film_id) === 0)
    {
      return false;
    }

    $episode_id = $filmPage->attr('data-episode-id');
    if (isset($episode_id) === 0)
    {
      return false;
    }

    $getSrcs = $client->request('GET', '/api/v2/films/'.$film_id.'/episodes/'.$episode_id, []);
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

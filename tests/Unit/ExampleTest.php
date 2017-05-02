<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ExampleTest extends TestCase
{
  /**
  * A basic test example.
  *
  * @return void
  */
  public function testBasicTest()
  {
    $this->results = [
      'm'    => null, // Messageage
      's'    => false, // Success status
      'srcs' => [
        /*[

        ]*/
      ],
    ];
    $parseUrl = parse_url('http://anime47.com/phim/pocket-monsters-sun-moon/m6003.html');
    $client = new Client([
      'base_uri' => 'http://'.$parseUrl['host'],
      'http_errors' => false,
      'allow_redirects' => false,
      'headers' => [
        'X-Requested-With'          => 'XMLHttpRequest',
        'Referer'                   => $parseUrl['host'],
        'Upgrade-Insecure-Requests' => 1,
        //'User-Agent'                => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
      ],
    ]);

    $res = $client->request('GET', $parseUrl["path"], []);
    if ($res->getStatusCode() !== 200)
    {
      return false;
    }
    
    // Start
    $dom = new Crawler((string)$res->getBody());
    $views = sprintf('%d', $dom->filter('.movie-dd')->last()->text());
    print_r($views);

    return true;
  }
}

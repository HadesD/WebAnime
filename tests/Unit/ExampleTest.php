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
    $parse_url = parse_url('http://anime47.com/phim/rokudenashi-majutsu-koushi-to-akashic-records/m6152.html');
    $base_uri = 'http://anime47.com';
    $client = new Client([
      'base_uri' => $base_uri,
      'http_errors' => false,
      'allow_redirects' => false,
      'headers' => [
        'Upgrade-Insecure-Requests' => 1,
      ]
    ]);

    $res = $client->request('GET', $parse_url['path'], []);

    if ($res->getStatusCode() !== 200)
    {
      return;
    }
    $dom = new Crawler((string)$res->getBody());
    $btns = $dom->filter('.btn-block a');
    if (count($btns) === 0)
    {
      return;
    }

    preg_match('/^\.(.*)/i', $btns->last()->attr('href'), $m);
    if (isset($m[1]) === false)
    {
      return;
    }

    $res = $client->request('GET', $m[1], []);
    if ($res->getStatusCode() !== 200)
    {
      return;
    }

    $dom = new Crawler((string)$res->getBody());
    $servers = $dom->filter('.server a');
    if (count($servers) === 0)
    {
      return;
    }

    $servers->reduce(function (Crawler $node, $i) {
      $source = $node->attr('href');
      if ((empty($source) === true) || (filter_var($source, FILTER_VALIDATE_URL) === false))
      {
        return;
      }

      // Insert/Update Episode to database
      $episode = Episode::firstOrNew(
        [
          'source' => $source,
        ],
        [
          'name' => trim($node->text()),
          'film_id' => $film->id,
        ]
      );
      $episode->save();
    });

  }
}

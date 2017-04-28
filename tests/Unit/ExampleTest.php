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
      $base_uri = 'http://anime47.com';
      $client = new Client([
        'base_uri' => $base_uri,
        'http_errors' => false,
        'allow_redirects' => false,
      ]);
      $source = $base_uri."/phim/okusama-ga-seitokaichou/m5076.html";

      $filmData = @$client->request('GET', $source, []);
      $filmCrawler = new Crawler((string)$filmData->getBody());

      $filmContent = $filmCrawler->filter('.movie-info')->last();
      $catList = $filmContent->filter('.dd-cat a');
      foreach($catList as $cat)
      {
       print_r($cat->textContent);
      }
      return $client;
    }
}

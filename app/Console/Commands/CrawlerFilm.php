<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Film;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\API\Imgur\UploadIMGController;

class CrawlerFilm extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'crawler:film';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'Crawler link film from another website';

  protected $listDomain, $pickDomain;

  private $nextPickDomain;

  /**
  * Create a new command instance.
  *
  * @return void
  */
  public function __construct()
  {
    parent::__construct();

    $this->listDomain = [
      //'vuighe.net',
      'anime47.com',
    ];

    $this->nextPickDomain = storage_path('logs/CrawlerFilmNextDomain.log');
  }

  /**
  * Execute the console command.
  *
  * @return mixed
  */
  public function handle()
  {
    $this->pickDomain = 0;
    if (file_exists($this->nextPickDomain))
    {
      $this->pickDomain += file_get_contents($this->nextPickDomain);
      if ($this->pickDomain >= (count($this->listDomain)-1))
      {
        $this->pickDomain = 0;
      }
    }
    file_put_contents($this->nextPickDomain, $this->pickDomain+1);

    $funcName = studly_case((preg_replace('/[^A-Za-z0-9\-]/', '_', $this->listDomain[$this->pickDomain])));
    if(method_exists($this, $funcName))
    {
      $this->line("{$this->listDomain[$this->pickDomain]} is being crawler.");
      $this->{$funcName}();
    }
  }

  function VuigheNet()
  {
    $base_uri = 'http://'.$this->listDomain[$this->pickDomain];
    $client = new Client([
      'base_uri' => $base_uri,
      'http_errors' => false,
      'allow_redirects' => false,
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest',
        'Referer'          => $base_uri,
      ],
    ]);

    $limit = 1;
    $offset = 0;

    $uploadThumbs = new UploadIMGController;

    while (true)
    {
      $url = "/api/v2/films?limit={$limit}&offset={$offset}";
      $res = $client->request('GET', $url, []);
      if ($res->getStatusCode() !== 200)
      {
        continue;
      }

      $data = json_decode($res->getBody(), true);
      if (isset($data['data']) !== true)
      {
        continue;
      }

      if ($offset === 0)
      {
        $bar = $this->output->createProgressBar($data['total']);
        $bar->start();
        $limit = 40;
      }

      if (isset($bar) !== true)
      {
        continue;
      }

      // Update ProgressBar and new Request offset
      $bar->advance(count($data['data']));
      $offset += count($data['data']);

      // Update Database
      foreach ($data['data'] as $film)
      {
        Film::updateOrCreate(
          [
            'source' => "{$base_uri}/{$film['slug']}",
          ],
          [
            'name' => $film['name'],
            'description' => $film['description'],
            'thumbnail' => $uploadThumbs->uploadURL($film['thumbnail']),
          ]
        );
      }

      // Check end of job
      if ($offset >= $bar->getMaxSteps())
      {
        break;
      }
    }
    $bar->finish();
    $this->info("\n{$bar->getMaxSteps()} films is crawled.");
  }

  public function Anime47Com()
  {
    $base_uri = 'http://'.$this->listDomain[$this->pickDomain];
    $client = new Client([
      'base_uri' => $base_uri,
      'http_errors' => false,
      'allow_redirects' => false,
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest',
        'Referer'          => $base_uri,
      ],
    ]);

    $uploadThumbs = new UploadIMGController;

    $page = 1;

    while (true)
    {
      $url = "/tim-nang-cao/?status=&season=&year=&sort=&page={$page}";
      $res = $client->request('GET', $url, []);
      if ($res->getStatusCode() !== 200)
      {
        continue;
      }

      $crawler = new Crawler((string)$res->getBody());

      $filmListBox = $crawler->filter('.col-lg-8 > .movie-list-index');
      if (count($filmListBox) <= 0)
      {
        continue;
      }

      if ($page === 1)
      {
        $getQuery = explode('?', $filmListBox->filter('.pagination a')->last()->attr('href'));
        if (isset($getQuery[1]) === false)
        {
          continue;
        }
        parse_str($getQuery[1], $parse_str);
        if (isset($parse_str['page']) === false)
        {
          continue;
        }
        $bar = $this->output->createProgressBar($parse_str['page']);
        $bar->start();
      }

      if (isset($bar) === false)
      {
        continue;
      }

      $filmList = $filmListBox->filter('#movie-last-movie > li > a');
      if (count($filmList) <= 0)
      {
        continue;
      }
      $bar2 = $this->output->createProgressBar(count($filmList));
      $bar2->start();
      $this->info("\nPage {$page} is processing");
      $filmList->reduce(function (Crawler $node, $i) use ($client, $base_uri, $uploadThumbs, $bar2)
      {
        if (preg_match('/^\.(.*)/i', $node->attr('href'), $m) === false)
        {
          return;
        }

        $source = $base_uri.$m[1];

        $filmData = $client->request('GET', $source, []);
        if ($filmData->getStatusCode() !== 200)
        {
          return;
        }

        $filmCrawler = new Crawler((string)$filmData->getBody());

        $filmContent = $filmCrawler->filter('.movie-info')->last();
        if (count($filmContent) <= 0)
        {
          return;
        }

        $bar2->advance();

        Film::updateOrCreate(
          [
            'source' => $source,
          ],
          [
            'name' => $filmContent->filter('.movie-detail > h1 > .title-1')->text(),
            'description' => $filmContent->filter('#film-content > p')->first()->text(),
            'thumbnail' => $uploadThumbs->uploadURL($filmContent->filter('.movie-image > .movie-l-img > img')->first()->attr('src')),
          ]
        );
      });
      $this->info("\nPage {$page} is completed: {$bar2->getProgress()}/{$bar2->getMaxSteps()}\n");
      $bar2->finish();

      // Update Process
      $bar->advance();
      $page++;

      // Check end of job
      if ($page >= $bar->getMaxSteps())
      {
        break;
      }
    }

    $bar->finish();
    $this->info("\n{$bar->getMaxSteps()} pages is crawled.");
  }
}

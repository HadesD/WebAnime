<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Film;
use App\Tag;
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
      'vuighe.net',
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
      if ($this->pickDomain > (count($this->listDomain)-1))
      {
        $this->pickDomain = 0;
      }
    }
    file_put_contents($this->nextPickDomain, $this->pickDomain+1);

    $funcName = studly_case_domain($this->listDomain[$this->pickDomain]);
    if(method_exists($this, $funcName))
    {
      $this->line("{$this->listDomain[$this->pickDomain]} is being crawler.");
      call_user_func([$this, $funcName]);
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
      $res = @$client->request('GET', $url, []);
      if ($res->getStatusCode() !== 200)
      {
        continue;
      }

      $data = json_decode($res->getBody(), true);
      if (isset($data['data']) !== true)
      {
        continue;
      }

      if (isset($bar) === false)
      {
        $bar = $this->output->createProgressBar($data['total']);
        $bar->start();
        $bar->advance($offset);
        $limit = 40;
      }

      // Update new Request offset
      $offset += count($data['data']);

      // Update Database
      foreach ($data['data'] as $film_data)
      {
        // Update ProgressBar
        $bar->advance();

        // Update table Films
        $film = Film::firstOrNew(
          [
            'source' => "{$base_uri}/{$film_data['slug']}",
          ],
          [
            'name' => $film_data['name'],
            'description' => $film_data['description'],
          ]
        );
        if (empty($film->thumbnail) === true)
        {
          $film->thumbnail = @$uploadThumbs->uploadURL($film_data['thumbnail']);
        }
        // Update table Tags
        if (isset($film_data['genres']['data']) === true)
        {
          foreach ($film_data['genres']['data'] as $key => $genre_data)
          {
            $tag = Tag::firstOrNew(
              [
                'name' => $genre_data['name'],
              ]
            );
            if (isset($film->tags) === false)
            {
              $film->tags = [];
            }
            if (in_array($tag->id, $film->tags) === false)
            {
              $_film_tags = $film->tags;
              $_film_tags[] = $tag->id;
              $film->tags = $_film_tags;
            }
            $tag->save();
          }
        }
        $film->save();
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

    $page = 35;

    while (true)
    {
      $currentPage = $page;
      $url = "/tim-nang-cao/?status=&season=&year=&sort=&page={$currentPage}";
      $res = @$client->request('GET', $url, []);
      if ($res->getStatusCode() !== 200)
      {
        continue;
      }

      // Update Process
      $page++;

      $crawler = new Crawler((string)$res->getBody());

      $filmListBox = $crawler->filter('.col-lg-8 > .movie-list-index');
      if (count($filmListBox) === 0)
      {
        continue;
      }

      if (isset($bar) === false)
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
        $bar->advance($currentPage-1);
      }
      $bar->advance();

      $filmList = $filmListBox->filter('#movie-last-movie > li > a');
      if (count($filmList) <= 0)
      {
        continue;
      }
      $bar2 = $this->output->createProgressBar(count($filmList));
      $this->info("\nPage {$currentPage} is processing");
      $bar2->start();
      $filmList->reduce(function (Crawler $node, $i) use ($client, $base_uri, $uploadThumbs, $bar2) {
        preg_match('/^\.(.*)/i', $node->attr('href'), $m);
        if (isset($m[1]) === false)
        {
          return;
        }

        $source = $base_uri.$m[1];

        $filmData = @$client->request('GET', $source, []);
        if ($filmData->getStatusCode() !== 200)
        {
          return;
        }

        $filmCrawler = new Crawler((string)$filmData->getBody());

        $filmContent = $filmCrawler->filter('.movie-info')->last();
        $bar2->advance();
        if (count($filmContent) === 0)
        {
          $this->error("\nSource {$source} is not has a info.");
          return;
        }

        if (count($filmContent->filter('.movie-detail > h1 > .title-1')) === 0)
        {
          $this->error("\nSource {$source} is not has a title.");
          return;
        }

        $film = Film::firstOrNew(
          [
            'source' => $source,
          ],
          [
            'name' => $filmContent->filter('.movie-detail > h1 > .title-1')->text(),
          ]
        );
        if (count($filmContent->filter('#film-content > p')) > 0)
        {
          $film->description = $filmContent->filter('#film-content > p')->first()->text();
        }
        if (empty($film->thumbnail) === true)
        {
          $film->thumbnail = @$uploadThumbs->uploadURL($filmContent->filter('.movie-image > .movie-l-img > img')->first()->attr('src'));
        }
        // Update table Tags
        $catList = $filmContent->filter('.dd-cat a');
        if (count($catList) > 0)
        {
          foreach($catList as $cat)
          {
            if (isset($cat->textContent) === false)
            {
              continue;
            }
            $tag = Tag::firstOrNew(
              [
                'name' => trim($cat->textContent),
              ]
            );
            if (isset($film->tags) === false)
            {
              $film->tags = [];
            }
            if (in_array($tag->id, $film->tags) === false)
            {
              $_film_tags = $film->tags;
              $_film_tags[] = $tag->id;
              $film->tags = $_film_tags;
            }
            $tag->save();
          }
        }
        $film->save();
      });
      $this->info("\nPage {$currentPage} is completed: {$bar2->getProgress()}/{$bar2->getMaxSteps()}\n");
      $bar2->finish();

      // Check end of job
      if ($currentPage >= $bar->getMaxSteps())
      {
        break;
      }
    }

    $bar->finish();
    $this->info("\n{$bar->getMaxSteps()} pages is crawled.");
  }
}

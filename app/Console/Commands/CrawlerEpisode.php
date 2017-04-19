<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Film;
use App\Episode;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
// use App\Http\Controllers\API\Imgur\UploadIMGController;

class CrawlerEpisode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:episode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler link episode of film';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $films = Film::whereNotNull('source')->where('source', 'like', '%vuighe.net%')->orderBy('created_at', 'ASC')->take(10)->get();
      
      $total = count($films);
      if ($total === 0)
      {
        return;
      }
      
      $bar = $this->output->createProgressBar($total);
      $this->line("Crawler episode of {$total} films is started");
      foreach ($films as $film)
      {
        $bar->advance();
        $parse_url = parse_url($film->source);
        $funcName = studly_case_domain($parse_url['host']);
        if(method_exists($this, $funcName) === false)
        {
          continue;
        }
        call_user_func([$this, $funcName], $film);
      }
      
      $bar->finish();
      
      $this->line("\nCrawler episode of {$total} films is completed");
    }
  
  public function VuigheNet($film)
  {
    $parse_url = parse_url($film->source);
    $base_uri = 'http://'.$parse_url['host'];
    $client = new Client([
      'base_uri' => $base_uri,
      'http_errors' => false,
      'allow_redirects' => false,
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest',
        'Referer'          => $base_uri,
      ],
    ]);
    
    $res = $client->request('GET', $parse_url['path'], []);
    if ($res->getStatusCode() !== 200)
    {
      return;
    }
    
    preg_match('/id="filmPage".*?data-id="(\d+)"/msi', $res->getBody(), $film_id);
    if (isset($film_id[1]) === false)
    {
      return;
    }
    
    $episodes = $client->request('GET', "/api/v2/films/{$film_id[1]}/episodes?sort=name");// /api/v2/films/{$film_id}/seasons
    if ($episodes->getStatusCode() !== 200)
    {
      return;
    }
    $ep_json = json_decode($episodes->getBody(), true);
    if (isset($ep_json['data']) === false)
    {
      return;
    }
    $bar = $this->output->createProgressBar(count($ep_json['data']));
    foreach ($ep_json['data'] as $data)
    {
      $bar->advance();
      $source = $base_uri.$data['link'];
      $episode = Episode::firstOrNew(
        [
          'source' => $source,
        ],
        [
          'name' => $data['full_name'],
          'film_id' => $film->id,
        ]
      );
      $episode->save();
    }
    $bar->finish();
  }
}

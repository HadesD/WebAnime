<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Film;

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
  
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      
      $this->listDomain = [
        'vuighe.net', //'anime47.com'
      ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $this->pickDomain = rand(0, count($this->listDomain)-1);
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
            ['source' => "{$base_uri}/{$film['slug']}"],
            [
              'name' => $film['name'],
              'description' => $film['description'],
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
}

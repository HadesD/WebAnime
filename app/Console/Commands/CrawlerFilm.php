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

    protected $listDomain;
    private $pickDomain;
  
    protected $results;
  
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
      
      $this->results = [
        'name' => null,
        'description' => null,
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
      $client = new Client([
        'base_uri' => 'http://'.$this->listDomain[$this->pickDomain],
        'http_errors' => false,
        'allow_redirects' => false,
        'headers' => [
          'X-Requested-With' => 'XMLHttpRequest',
          'Referer'          => $this->listDomain[$this->pickDomain],
        ],
      ]);
      
      $limit = 0;
      $offset = 0;
      
      while (true)
      {
        $url = "/api/v2/films?limit={$limit}&offset={$offset}";
        $res = $client->request('GET', $url, []);
        if ($res->getStatusCode() === 200)
        {
          $data = json_decode($res->getBody(), true);
          if (isset($data['data']))
          {
            if ($offset === 0)
            {
              $bar = $this->output->createProgressBar($data['total']);
              //$bar->setFormat('verbose');
              $limit = 40;
            }
            if (isset($bar) === true)
            {
              $bar->advance(count($data['data']));
              $offset += count($data['data']);
              
              if ($offset >= $bar->getMaxSteps())
              {
                break;
              }
            }
          }
        }
      }
      $bar->finish();
      $this->line("{$bar->getMaxSteps()} is crawled.");
      
    }
}

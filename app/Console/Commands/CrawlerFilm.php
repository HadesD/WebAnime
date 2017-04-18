<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
      
      $this->results = [[
        'name' => null,
        'description' => null,
      ]];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $domain = rand(0, count($this->listDomain)-1);
      $funcName = studly_case((preg_replace('/[^A-Za-z0-9\-]/', '_', $this->listDomain[$domain])));
      if(method_exists($this, $funcName))
      {
        $this->{$funcName}();
      }
    }
  
    function VuigheNet()
    {
      print_r("sldkf");
    }
}

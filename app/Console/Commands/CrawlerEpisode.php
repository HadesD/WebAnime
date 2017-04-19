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
      $films = Film::whereNotNull('source')->orderBy('created_at', 'ASC')->take(10)->get();
      
      $total = count($films);
      if ($total <= 0)
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
        $this->{$funcName}($film->source);
      }
      
      $bar->finish();
      
      $this->line("\nCrawler episode of {$total} films is completed");
    }
  
  public function VuigheNet($url)
  {
    
  }
}

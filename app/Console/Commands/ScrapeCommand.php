<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bot = new \App\Scraper\CrawlData();
        $bot->scrape();
    }
}

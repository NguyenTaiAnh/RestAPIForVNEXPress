<?php
namespace App\Scraper;

use App\Models\Posts;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class CrawlData {
    public function scrape($urlCLick = null){
        print "Start........"."\n";
        if($urlCLick === null){
            $url = 'https://vnexpress.net/thoi-su';
        }else{
            $url ='https://vnexpress.net'.$urlCLick;
        }
        Log::info(Carbon::now('Asia/Ho_Chi_Minh'));
        Log::debug(Carbon::now('Asia/Ho_Chi_Minh'));
        $client = new Client(HttpClient::create(['timeout' => 1000]));
        $crawler = $client->request('GET', $url);
        $crawler->filter('div.list-news-subfolder > article.item-news')->each(
            function (Crawler $crawler) {

                if($crawler->filter('h3.title-news > a')->count() > 0) {

                    $title =$crawler->filter('h3.title-news > a')->attr('title');
                    $check = Posts::where('title','like', '%'.$title.'%')->first();
                    if (!$check){
                        print $crawler->filter('h3.title-news > a')->attr('href')."\n";
                        print $crawler->filter('div.thumb-art > a.thumb')->html()."\n"."\n";
                        $this->getLinkPost($crawler->filter('h3.title-news > a')->attr('href'));
                        print $crawler->filter('h3.title-news > a')->attr('title')."\n";
                        print $crawler->filter('p.description > a')->text()."\n";
                        print "\n";

                        $post = new Posts();
                        $post->title = $crawler->filter('h3.title-news > a')->attr('title');
                        $post->image =$crawler->filter('div.thumb-art > a.thumb')->html();
                        $post->link_post = $crawler->filter('h3.title-news > a')->attr('href');
                        $post->description = $crawler->filter('p.description > a')->text();
                        $post->content = $this->getLinkPost($crawler->filter('h3.title-news > a')->attr('href'));
                        $post->save();
                    }
//
//                    echo $articles->html();
//                    print $crawler->filter('h3.title-news > a')->attr('href')."\n";
//                   $results[] = (object) [
//                       'link' => $crawler->filter('h3.title-news > a')->attr('href'),
//                       'title' => $crawler->filter('h3.title-news > a')->attr('title'),
//                       'description' => $crawler->filter('p.description > a')->text(),
//                       'content' => $this->getLinkPost($crawler->filter('h3.title-news > a')->attr('href'))
//                   ];
//                   dump($results);
                }
            }
        );
        if($crawler->filter('div.button-page > a.next-page')->count() > 0) {
            $this->scrape($crawler->filter('div.button-page > a.next-page')->attr('href'));
        }
        print "End........"."\n";

    }

    public function getLinkPost($url){
        $client = new Client(HttpClient::create(['timeout' => 1000]));
        $crawler = $client->request('GET', $url);
        if($crawler->filter('div.container > div.sidebar-1') && $crawler->filter('div.container > div.sidebar-1')->count() > 0) {

            return $crawler->filter('div.sidebar-1')->html()??$crawler->filter('div.sidebar-1')->html()."\n";
        }

    }


}

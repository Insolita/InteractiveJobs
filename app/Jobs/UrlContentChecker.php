<?php

namespace App\Jobs;

use App\Lib\InteractiveJobs\Contracts\Reportable;
use App\Lib\InteractiveJobs\InteractiveJob;
use function collect;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use function file_get_contents;
use Symfony\Component\DomCrawler\Link;

class UrlContentChecker extends InteractiveJob implements Reportable
{
    public $tries = 3;
    
    public function execute()
    {
        $url = $this->jobModel->payload['url'];
        $content = file_get_contents($url);
        $this->jobModel->updateProgress(45, 'Url Content was loaded');
        
        $uri = new Uri($url);
        $crawler = new Crawler(null, $url);
        $crawler->addHtmlContent($content);
        
        $this->report['title'] = $crawler->filter('title')->text();
        Log::info('title = '.$this->report['title']);
        
        $links = collect($crawler->filter('a')->links());
        $this->jobModel->updateProgress(75, 'Links was parsed');
        $this->report['totalLinks'] = $links->count();
        
        $externalLinks = $links->reject(function (Link $link) use ($uri){
            return Str::contains($link->getUri(), $uri->getHost());
        });
        $this->report['externalLinks'] = $externalLinks->count();
        Log::info('external Links', $externalLinks->map->getUri()->toArray());
    }
}

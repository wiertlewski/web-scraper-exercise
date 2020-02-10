<?php
declare(strict_types=1);

use Arek\WebScraperExercise\VidexCrawler;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class VidexCrawlerTest extends AbstractTestCase
{
    public function testCrawl()
    {
        $crawlerMock = Mockery::mock(Crawler::class);
        $crawlerMock->shouldReceive('filter')->with('#subscriptions')->andReturnSelf();
        $crawlerMock->shouldReceive('each');

        $clientMock = Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->with('GET', VidexCrawler::URL)->andReturn($crawlerMock);

        $testObject = (new VidexCrawler($clientMock));

        $this->assertEquals('[]', $testObject->crawl());
    }
}

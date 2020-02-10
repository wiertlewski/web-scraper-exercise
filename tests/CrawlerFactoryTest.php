<?php
declare(strict_types=1);

use Arek\WebScraperExercise\CrawlerFactory;
use Arek\WebScraperExercise\CrawlerNotFoundException;
use Arek\WebScraperExercise\VidexCrawler;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerFactoryTest extends AbstractTestCase
{
    public function testConstruct()
    {
        $clientMock = Mockery::mock(Client::class);
        $testObject = new CrawlerFactory($clientMock);

        $this->assertInstanceOf(CrawlerFactory::class, $testObject);
    }

    public function testCrawlerNotFound()
    {
        $this->expectException(CrawlerNotFoundException::class);

        $clientMock = Mockery::mock(Client::class);
        $testObject = new CrawlerFactory($clientMock);

        $testObject->crawl('other');

    }

    public function testVidexCrawl()
    {
        $crawlerMock = Mockery::mock(Crawler::class);
        $crawlerMock->shouldReceive('filter')->with('#subscriptions')->andReturnSelf();
        $crawlerMock->shouldReceive('each');

        $clientMock = Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->with('GET', VidexCrawler::URL)->andReturn($crawlerMock);

        $testObject = new CrawlerFactory($clientMock);

        $this->assertEquals('[]', $testObject->crawl(CrawlerFactory::VIDEX_CRAWLER));
    }
}

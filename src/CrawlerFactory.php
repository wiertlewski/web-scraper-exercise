<?php
declare(strict_types=1);

namespace Arek\WebScraperExercise;

use Goutte\Client;

/**
 * Class CrawlerFactory
 * @package Arek\WebScraperExercise
 */
class CrawlerFactory
{
    public const CRAWLERS = [
        self::VIDEX_CRAWLER,
    ];

    public const VIDEX_CRAWLER = 'videx';

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $crawlerName
     *
     * @return string
     * @throws CrawlerNotFoundException
     */
    public function crawl(string $crawlerName): string
    {
        switch ($crawlerName) {
            case self::VIDEX_CRAWLER:
                return (new VidexCrawler($this->client))->crawl();
            default:
                throw new CrawlerNotFoundException();
        }
    }
}

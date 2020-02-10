<?php
declare(strict_types=1);

namespace Arek\WebScraperExercise;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractCrawler
 * @package Arek\WebScraperExercise
 */
abstract class AbstractCrawler
{
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
     * @return string
     */
    public function crawl(): string
    {
        $crawler = $this->fetch();

        return json_encode($this->parse($crawler), JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param Crawler $crawler
     *
     * @return array
     */
    abstract protected function parse(Crawler $crawler): array;

    /**
     * @return string
     */
    abstract protected function url(): string;

    /**
     * @return Crawler
     */
    private function fetch(): Crawler
    {
        return $this->client->request('GET', $this->url());
    }
}

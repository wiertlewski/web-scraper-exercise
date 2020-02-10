<?php
declare(strict_types=1);

use Goutte\Client;
use Arek\WebScraperExercise\CrawlerFactory;
use Arek\WebScraperExercise\CrawlerNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

$crawlerFactory = new CrawlerFactory(new Client());

try {
    $crawlerName = $argv[1] ?? '';
    echo $crawlerFactory->crawl($crawlerName);
} catch (CrawlerNotFoundException $exception) {
    echo "Crawler Not Found.\n\nUsage:\n\n";
    foreach (CrawlerFactory::CRAWLERS as $crawlerName) {
        echo sprintf("php %s/scraper.php %s\n", __DIR__, $crawlerName);
    }
} catch (Exception $exception) {
    echo "Something Went Wrong!\n";
}

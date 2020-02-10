<?php
declare(strict_types=1);

namespace Arek\WebScraperExercise;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class VidexCrawler
 * @package Arek\WebScraperExercise
 */
class VidexCrawler extends AbstractCrawler
{
    public const URL = 'https://videx.comesconnected.com/';

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param Crawler $crawler
     * @return array
     */
    protected function parse(Crawler $crawler): array
    {
        $crawler->filter('#subscriptions')->each(function ($node) {
            $this->parsePackage($node);
        });

        usort($this->options, function ($left, $right) {
            return $left['price'] < $right['price'];
        });

        return $this->options;
    }

    /**
     * @return string
     */
    protected function url(): string
    {
        return self::URL;
    }

    /**
     * @param Crawler $node
     */
    private function parsePackage(Crawler $node): void
    {
        $isAnnualSubscription = $this->isAnnualSubscription($node);

        $node->filter('div.package')->each(function ($node) use ($isAnnualSubscription) {
            $this->options[] = [
                'option title' => $this->optionTitle($node),
                'description' => $this->description($node),
                'price' => $this->price($node, $isAnnualSubscription),
                'discount' => $this->discount($node, $isAnnualSubscription),
            ];
        });
    }

    /**
     * @param Crawler $node
     *
     * @return bool
     */
    private function isAnnualSubscription(Crawler $node): bool
    {
        return $node->filter('h2')->text() === 'Annual Subscription Packages';
    }

    /**
     * @param Crawler $node
     *
     * @return string
     */
    private function optionTitle(Crawler $node): string
    {
        return $node->filter('h3')->text();
    }

    /**
     * @param Crawler $node
     *
     * @return string
     */
    private function description(Crawler $node): string
    {
        return $node->filter('div.package-name')->text();
    }

    /**
     * @param Crawler $node
     * @param bool    $isAnnualSubscription
     *
     * @return float
     */
    private function price(Crawler $node, bool $isAnnualSubscription): float
    {
        $priceText = $node->filter('span.price-big')->text();

        $price = floatval(preg_replace('/[^\d.]/', '', $priceText));

        if ($isAnnualSubscription) {
            return $price;
        }

        return $price * 12;
    }

    /**
     * @param Crawler $node
     * @param bool    $isAnnualSubscription
     *
     * @return int
     */
    private function discount(Crawler $node, bool $isAnnualSubscription): int
    {
        if ($isAnnualSubscription) {
            $discountText = $node->filter('div.package-price p')->text();

            return (int) filter_var($discountText, FILTER_SANITIZE_NUMBER_INT);
        }

        return 0;
    }
}

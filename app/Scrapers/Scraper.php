<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Http;
use App\Utilities\Currency;

abstract class Scraper
{
    public static $failedSites = 0;
    public static $failedListings = 0;
    public static $children = [
        'reed' => ReedScraper::class,
        // 'indeed' => IndeedScraper::class,
        'cwjobs' => CWJobsScraper::class,
    ];

    public $links;
    protected $searchTerm;
    protected $searchUrl;
    protected $siteName;
    protected $listingLinksQuery;
    protected $headers = [
        'User-Agent' => 'Career-Curator/v1.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate, br',
    ];

    public function getDom($url): ?\DOMDocument
    {
        $response = Http::withHeaders($this->headers)->get($url, ['allow_redirects' => false,]);

        if ($response->successful()) {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML($response->body());

            return $dom;
        } else {
            return null;
        }
    }

    public function setLinks(): void
    {
        $links = [];

        $dom = $this->getDom($this->searchUrl);
        if ($dom) {
            $xPath = new \DOMXPath($dom);
            $jobCards = $xPath->query($this->listingLinksQuery);

            foreach ($jobCards as $jobCard) {
                $links[] = [
                    'site' => $this->siteName,
                    'url' => $this->baseUrl . $jobCard->getAttribute('href'),
                ];
            }
        } else {
            self::$failedSites++;
        }

        $this->links = $links;
    }

    public function getListingData(): array
    {
        $listingData = [];

        foreach ($this->links as $link) {
            $dom = $this->getDom($link);
            if ($dom) {
                $listingData[] = [
                    'site' => $this->siteName,
                    'link' => $link,
                    'title' => $this->getTitle($dom),
                    'description' => $this->getDescription($dom),
                    'salaryRange' => $this->getSalaryRange($this->getSalary($dom)),
                ];
            } else {
                self::$failedListings++;
            }
        }

        return $listingData;
    }

    public function getSalaryRange($text): array
    {
        if ($text) {
            $values = Currency::extractMoneyValues($text);

            if (count($values) >= 2) {
                return [$values[0], $values[1]];
            }
        }

        return [0, 0];
    }

    abstract protected function getTitle(\DOMDocument $dom): string;
    abstract protected function getDescription(\DOMDocument $dom): string;
    abstract protected function getSalary(\DOMDocument $dom): string;
}
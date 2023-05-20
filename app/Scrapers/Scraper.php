<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Http;

abstract class Scraper
{
    protected $searchTerm;
    protected $searchUrl;
    protected $siteName;
    protected $headers = [
        'User-Agent' => 'Career-Curator/v1.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate, br',
    ];
    public static $failedSiteCalls = 0;
    public static $failedListingCalls = 0;

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

    public function getListingData(): array
    {
        $listingData = [];

        $links = $this->getListingLinks();
        foreach ($links as $link) {
            $dom = $this->getDom($link);
            if ($dom) {
                $listingData[] = [
                    'site' => $this->siteName,
                    'link' => $link,
                    'title' => $this->getTitle($dom),
                    'description' => $this->getDescription($dom),
                    'salaryRange' => $this->getSalaryRange($dom)
                ];
            } else {
                self::$failedListingCalls++;
            }
        }

        return $listingData;
    }

    abstract public function getListingLinks(): array;
    abstract protected function getTitle($dom): string;
    abstract protected function getDescription($dom): string;
    abstract protected function getSalaryRange($dom): array;
}
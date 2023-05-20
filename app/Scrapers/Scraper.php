<?php

namespace App\Scrapers;

class Scraper
{
    protected $searchTerm;
    protected $searchUrl;
    public static $failedCalls = 0;

    public function __construct($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function getSearchResultsXPath(): ?DOMXPath
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Career-Curator/v1.0',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
        ])->get($this->searchUrl, [
            'allow_redirects' => false,
        ]);

        if ($response->successful()) {
            $dom = new \DOMDocument();
            $dom->loadHTML($response->body());

            $xPath = new \DOMXPath($dom);

            return $xPath;
        } else {
            self::failedCalls++;

            return null;
        }
    }
}
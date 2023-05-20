<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Http;

class ReedScraper
{
    protected $searchTerm;
    protected $html;

    public function __construct($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function getListingLinks(): array
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Career-Curator/v1.0',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
        ])->get("https://www.reed.co.uk/jobs/{$this->searchTerm}-jobs", [
            'allow_redirects' => false,
        ]);

        if ($response->successful()) {
            $dom = new \DOMDocument();
            $dom->loadHTML($response->body());

            $xPath = new \DOMXPath($dom);
            $jobCards = $xPath->query("//*[@data-qa='job-card-title']");
            
            $links = [];
            foreach ($jobCards as $jobCard) {
                $links[] = $jobCard->getAttribute('href');
            }

            return $links;
        }
    }
}

<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Http;

class ReedScraper extends Scraper implements GetsListings
{
    protected $baseUrl = 'https://reed.co.uk';
    protected $html;

    public function __construct()
    {
        $this->searchUrl = "https://reed.co.uk/jobs/{$this->searchTerm}-jobs";
    }

    public function getListingLinks(): array
    {
        $links = [];

        $xPath = $this->getSearchResultsXPath();
        if ($xPath) {
            $jobCards = $xPath->query("//*[@data-qa='job-card-title']");

            foreach ($jobCards as $jobCard) {
                $links[] = $baseUrl . $jobCard->getAttribute('href');
            }
        }

        return $links;
    }

    // {title: '', description: ''}
    public function getListingData(): array
    {
        $links = $this->getListingLinks();

        foreach ($links as $link) {

        }
    }
}
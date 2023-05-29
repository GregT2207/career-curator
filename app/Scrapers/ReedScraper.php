<?php

namespace App\Scrapers;

class ReedScraper extends Scraper
{
    public $siteName = 'Reed';
    public $baseUrl = 'https://reed.co.uk';
    public $listingLinksQuery = '//*[@data-qa="job-card-title"]';

    public function __construct(string $searchTerm = null)
    {
        if ($searchTerm) {
            $this->searchTerm = $searchTerm;
            $this->searchUrl = "https://reed.co.uk/jobs/{$searchTerm}-jobs";
        }
    }

    protected function getTitle(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//meta[@itemprop="title"]');

        if ($results->item(0)) {
            $title = $results->item(0)->getAttribute('content');

            if ($title) {
                return $title;
            }
        }

        return '';
    }

    protected function getDescription(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//span[@itemprop="description"]');

        if ($results->item(0)) {
            $description = $dom->saveHTML($results->item(0));

            if ($description) {
                return $description;
            }
        }

        return '';
    }

    protected function getSalary(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//span[@data-qa="salaryLbl"]');

        if ($results->item(0)) {
            $text = $results->item(0)->textContent;

            if ($text) {
                return $text;
            }
        }

        return '';
    }
}
<?php

namespace App\Scrapers;

class CWJobsScraper extends Scraper
{
    protected $baseUrl = 'https://www.cwjobs.co.uk';

    public function __construct(string $searchTerm = null)
    {
        $this->searchTerm = $searchTerm;
        $this->searchUrl = "https://www.cwjobs.co.uk/jobs/{$searchTerm}?action=facet_selected%3bage%3b7&postedWithin=7";
        $this->siteName = 'CWJobs';
        $this->listingLinksQuery = '//*[@data-at="job-item-title"]';
    }

    protected function getTitle(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//div[contains(@class, "col-page-header")]//h1');

        if ($results->item(0)) {
            $title = $results->item(0)->firstChild->textContent;

            if ($title) {
                return $title;
            }
        }

        return '';
    }

    protected function getDescription(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//*[contains(concat(" ", normalize-space(@class), " "), "job-description")]');

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
        $results = $xPath->query('//*[contains(concat(" ", normalize-space(@class), " "), "salary") and contains(concat(" ", normalize-space(@class), " "), "icon")]');

        if ($results->item(0)) {
            $text = $results->item(0)->textContent;

            if ($text) {
                return $text;
            }
        }

        return '';
    }
}
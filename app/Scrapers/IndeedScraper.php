<?php

namespace App\Scrapers;

class IndeedScraper extends Scraper
{
    protected $baseUrl = 'https://uk.indeed.com/';

    public function __construct(string $searchTerm = null)
    {
        $this->searchTerm = $searchTerm;
        $this->searchUrl = "https://uk.indeed.com/jobs?q={$searchTerm}";
        $this->siteName = 'Indeed';
        $this->listingLinksQuery = '//*[contains(concat(" ", normalize-space(@class), " "), " jcs-JobTitle ")]';
    }

    protected function getTitle(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//*[contains(concat(" ", normalize-space(@class), " "), " jobsearch-JobInfoHeader-title ")]');

        if ($results->item(0)) {
            $title = $results->item(0)->firstChild->getAttribute('content');

            if ($title) {
                return $title;
            }
        }

        return '';
    }

    protected function getDescription(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//*[@id="jobDescriptionText"]');

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
        $results = $xPath->query('//*[@id="salaryInfoAndJobType"]');

        if ($results->item(0)) {
            $text = $results->item(0)->textContent;

            if ($text) {
                return $text;
            }
        }

        return '';
    }
}
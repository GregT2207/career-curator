<?php

namespace App\Scrapers;

class IndeedScraper extends Scraper
{
    protected $baseUrl = 'https://uk.indeed.com/';

    public function __construct($searchTerm)
    {
        $this->searchTerm = $searchTerm;
        $this->searchUrl = "https://uk.indeed.com/jobs?q={$searchTerm}";
        $this->siteName = 'Indeed';
        $this->listingLinksQuery = '//*[contains(concat(" ", normalize-space(@class), " "), " jcs-JobTitle ")]';
    }

    protected function getTitle($dom): string
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

    protected function getDescription($dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//*[@id="jobDescriptionText"]');

        if ($results->item(0)) {
            $description = $results->item(0)->textContent;

            if ($description) {
                return $description;
            }
        }

        return '';
    }

    protected function getSalaryRange($dom): array
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//*[@id="salaryInfoAndJobType"]');

        $range = [];
        $start = 0;
        $end = 0;

        if ($results->item(0)) {
            $text = $results->item(0)->firstChild->textContent;

            if ($text) {
                $range = explode(' - ', $text);

                if (is_array($range)) {
                    $start = preg_replace('/[^0-9]/', '', $range[0]);
                    $end = preg_replace('/[^0-9]/', '', end($range));
                }
            }
        }

        return [intval($start), intval($end)];
    }
}
<?php

namespace App\Scrapers;

class ReedScraper extends Scraper
{
    public $siteName = 'Reed';
    public $baseUrl = 'https://reed.co.uk';
    public $listingLinksQuery = '//*[@data-qa="job-card-title"]';

    public function __construct($searchTerm = null)
    {
        if ($searchTerm) {
            $this->searchTerm = $searchTerm;
            $this->searchUrl = "https://reed.co.uk/jobs/{$searchTerm}-jobs";
        }
    }

    protected function getTitle($dom): string
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

    protected function getDescription($dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//span[@itemprop="description"]');

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
        $results = $xPath->query('//span[@data-qa="salaryLbl"]');

        $range = [];
        $start = 0;
        $end = 0;

        if ($results->item(0)) {
            $text = $results->item(0)->textContent;

            if ($text) {
                $range = explode(' - ', $text);

                if (is_array($range)) {
                    $start = preg_replace('/[^0-9.]+/', '', $range[0]);
                    $end = preg_replace('/[^0-9.]+/', '', end($range));
                }
            }
        }

        return [floatval($start), floatval($end)];
    }
}
<?php

namespace App\Scrapers;

class ReedScraper extends Scraper
{
    protected $baseUrl = 'https://reed.co.uk';

    public function __construct($searchTerm)
    {
        $this->searchTerm = $searchTerm;
        $this->searchUrl = "https://reed.co.uk/jobs/{$searchTerm}-jobs";
        $this->siteName = 'Reed';
    }

    public function getListingLinks(): array
    {
        $links = [];

        $dom = $this->getDom($this->searchUrl);
        if ($dom) {
            $xPath = new \DOMXPath($dom);
            $jobCards = $xPath->query('//*[@data-qa="job-card-title"]');

            foreach ($jobCards as $jobCard) {
                $links[] = $this->baseUrl . $jobCard->getAttribute('href');
            }
        } else {
            self::$failedSiteCalls++;
        }

        return $links;
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
                    $start = preg_replace('/[^0-9]/', '', $range[0]);
                    $end = preg_replace('/[^0-9]/', '', end($range));
                }
            }
        }

        return [intval($start), intval($end)];
    }
}
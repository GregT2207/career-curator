<?php

namespace App\Scrapers;

class CWJobsScraper extends Scraper
{
    protected $baseUrl = 'https://www.cwjobs.co.uk';

    public function __construct(string $searchTerm = null)
    {
        $this->searchTerm = $searchTerm;
        $this->searchUrl = "https://www.cwjobs.co.uk/jobs/{$searchTerm}";
        $this->siteName = 'CWJobs';
        $this->listingLinksQuery = '//*[@data-at="job-item-title"]';
    }

    protected function getTitle(\DOMDocument $dom): string
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//div[contains(concat(" ", normalize-space(@class), " "), " row title ")]/h1');

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

    protected function getSalaryRange(\DOMDocument $dom): array
    {
        $xPath = new \DOMXPath($dom);
        $results = $xPath->query('//*[contains(concat(" ", normalize-space(@class), " "), "salary") and contains(concat(" ", normalize-space(@class), " "), "icon")]');

        $range = [];
        $start = 0;
        $end = 0;

        if ($results->item(0)) {
            $text = $results->item(0)->textContent;

            if ($text) {
                $separator = '';
                if (str_contains($text, ' - ')) {
                    $separator = ' - ';
                } else if (str_contains($text, ' to ')) {
                    $separator = ' to ';
                }

                if ($separator) {
                    $range = explode($separator, $text);

                    if (is_array($range)) {
                        $start = preg_replace('/[^0-9.]+/', '', $range[0]);
                        $end = preg_replace('/[^0-9.]+/', '', end($range));
                    }
                }
            }
        }

        return [floatval($start), floatval($end)];
    }
}
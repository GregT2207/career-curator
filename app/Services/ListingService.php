<?php

namespace App\Services;
use App\Scrapers\Scraper;
use App\Utilities\Arrays;

class ListingService
{
    // get array of links from all sites
    public function getLinks(array $data, bool $interspersed = false): array
    {
        $links = [];

        // 2d array containing each site's array of links
        $linksSeparated = [];
        foreach (Scraper::$children as $scraperClass) {
            $scraper = new $scraperClass($data['search']);

            $scraper->setLinks();
            if (count($scraper->links)) {
                if ($interspersed) {
                    // keep them in separate arrays
                    $linksSeparated[] = $scraper->links;
                } else {
                    // merge them all into one continous array
                    $links = array_merge($links, $scraper->links);
                }
            }
        }

        return $interspersed ? Arrays::mergeInterspersed($linksSeparated) : $links;
    }

    public function sortSalaryAsc(array $listings): array
    {

    }

    public function sortSalaryDesc(array $listings): array
    {
        
    }
}
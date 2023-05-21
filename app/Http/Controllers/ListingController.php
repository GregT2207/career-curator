<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Http\Resources\ListingResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Scrapers\Scraper;
use App\Scrapers\ReedScraper;
use App\Scrapers\IndeedScraper;
use App\Scrapers\CWJobsScraper;

class ListingController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $listings = [];

        $listingData = $this->getAllData($request->input('search_term'));
        if (count($listingData)) {
            foreach ($listingData as $data) {
                $listings[] = new Listing(
                    $data['site'],
                    $data['link'],
                    $data['title'],
                    $data['description'],
                    $data['salaryRange'],
                );
            }
        }

        return ListingResource::collection($listings)->additional([
            'failedSiteCalls' => Scraper::$failedSiteCalls,
            'failedListingCalls' => Scraper::$failedListingCalls,
        ]);
    }

    public function getAllData($searchTerm): array
    {
        $data = [];
        $scraperClasses = [
            ReedScraper::class,
            // IndeedScraper::class,
            CWJobsScraper::class,
        ];

        foreach ($scraperClasses as $scraperClass) {
            $scraper = new $scraperClass($searchTerm);

            $newData = $scraper->getListingData();
            if (count($newData)) {
                $data = array_merge($data, $newData);
            }
        }

        return $data;
    }
}

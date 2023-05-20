<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Listing;
use App\Http\Resources\ListingResource;
use App\Scrapers\Scraper;
use App\Scrapers\ReedScraper;

class ListingController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $listings = [];

        $listingData = $this->getAllData($request->input('search_term'));
        foreach ($listingData as $data) {
            $listings[] = new Listing(
                $data['site'],
                $data['link'],
                $data['title'],
                $data['description'],
                $data['salaryRange'],
            );
        }

        return ListingResource::collection($listings)->additional([
            'failedSiteCalls' => Scraper::$failedSiteCalls,
            'failedListingCalls' => Scraper::$failedListingCalls,
        ]);
    }

    public function getAllData($searchTerm): array
    {
        $data = [];

        $reedScraper = new ReedScraper($searchTerm);
        $data = array_merge($data, $reedScraper->getListingData());

        // $indeedScraper = new IndeedScraper($searchTerm);
        // $data[] = array_merge($data, $indeedScraper->getListingData());

        return $data;
    }
}

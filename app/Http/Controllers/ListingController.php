<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Scrapers\ReedScraper;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $listings = [];

        $listingData = $this->getAllData($request->searchTerm);
        foreach ($listingData as $data) {
            $listings[] = new Listing(
                $data['site'],
                $data['link'],
                $data['title'],
                $data['description'],
                $data['salaryRange'],
            );
        }

        return $listings;
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

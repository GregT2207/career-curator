<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scrapers\ReedScraper;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $listingData = [];

        $reedScraper = new ReedScraper($request->searchTerm)
        // $listingData[] = $reedScraper->
    }
}

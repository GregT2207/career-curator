<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Http\Requests\IndexLinksRequest;
use App\Services\ListingService;
use App\Http\Resources\ListingResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Scrapers\Scraper;
use App\Scrapers\ReedScraper;
use App\Scrapers\IndeedScraper;
use App\Scrapers\CWJobsScraper;

class ListingController extends Controller
{
    public function links(IndexLinksRequest $request, ListingService $service): array
    {
        $links = $service->getLinks($request->validated(), true);

        return [
            'data' => $links,
            'failedSites' => Scraper::$failedSites,
        ];
    }

    public function show(Request $request): ListingResource
    {
        $listing = [];

        if (!array_key_exists($request->site, Scraper::$children)) {
            abort(400, "Invalid site name \"$request->site\"");
        }

        $scraper = new Scraper::$children[$request->site]();
        $scraper->links = [$request->url];

        $data = $scraper->getListingData();
        if (count($data)) {
            $listing = new Listing(
                $data[0]['site'],
                $data[0]['link'],
                $data[0]['title'],
                $data[0]['description'],
                $data[0]['salaryRange'],
            );
        }

        return new ListingResource($listing);
    }

    // public function index(Request $request): AnonymousResourceCollection
    // {
    //     $listings = [];

    //     foreach ($request->links as $link) {
    //         if (!array_key_exists($link->site, Scraper::$children)) {
    //             abort(400, "Invalid site name \"$link->site\"");
    //         }

    //         $scraper = new Scraper::$children[$link->site]();
    //         $scraper->links = [$request->url];

    //         $listingData = $scraper->getListingData();
    //         if ($listingData) {
    //             $listing = new Listing(
    //                 $data['site'],
    //                 $data['link'],
    //                 $data['title'],
    //                 $data['description'],
    //                 $data['salaryRange'],
    //             );
    //         }
    //     }

    //     return ListingResource::collection($listings)->additional([
    //         'failedLinks' => Scraper::$failedLinks,
    //     ]);
    // }
}

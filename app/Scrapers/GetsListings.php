<?php

namespace App\Scrapers;

interface GetsListings
{
    public function getListingLinks();
    public function getListingData();
}
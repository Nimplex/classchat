<?php

namespace App\Controller;

use App\Service\ListingService;

class ListingController
{
    private ListingService $listingService;

    public function __construct(ListingService $listingService)
    {
        $this->listingService = $listingService;
    }

    /**
     * @param array<int,string|null> $query
     */
    public function listAll(array $query): string
    {
        $page = isset($query['page']) ? (int)$query['page'] : 1;
        return json_encode($this->listingService->listAll($page));
    }
}

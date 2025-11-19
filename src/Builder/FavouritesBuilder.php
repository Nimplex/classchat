<?php

namespace App\Builder;

use App\Model\Favourites;

class FavouritesBuilder
{
    public function make(): Favourites
    {
        require __DIR__ . '/../../bootstrap.php';

        /** @var PDO $db */
        $favourites = new Favourites($db);
        return $favourites;
    }
}

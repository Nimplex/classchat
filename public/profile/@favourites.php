<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/check-auth.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
$favouritesModel = (new App\Builder\FavouritesBuilder())->make();

$title = "Polubione";

$render_content = function () {
    global $favouritesModel;
    $html = "<h1>Polubione</h1><div>";
    foreach ($favouritesModel->find_by_user_id($_SESSION['user_id']) as $listing) {
        var_dump($listing);
        die;
        $html .= <<<HTML
        <div class="listing">
            <div class="listing-title">{$listing['title']}</div>
            <div class="listing-price">{$listing['price']}</div>
            <div class="listing-timestamp">{$listing['updated_at']}</div>
            <div class="listing-active">{$listing['active']}</div>
        </div>
        HTML;
    }
    $html .= "</div>";
    return $html;
};

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

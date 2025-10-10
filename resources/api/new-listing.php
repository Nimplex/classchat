<?php

[
    'title' => $title,
    'price' => $price,
    'description' => $description,
] = $_POST;

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
$listing = (new App\Builder\ListingBuilder())->make();

try {
    $status = $listing->create($title, $price, $description);
} catch (\InvalidArgumentException) {
    $status = 1;
}

// send '303 See Other' to redirect to page
header('Location: /listings/' . ($status == 0 ? "my-listings.php?ok=1" : "new.php?err=$status"), true, 303);

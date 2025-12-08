<?php

$msg = new App\FlashMessage();

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);

$raw_price = $_POST['price'] ?? '';
$normalized = str_replace(',', '.', $raw_price);
$price = filter_var($normalized, FILTER_VALIDATE_FLOAT);

if (!$title || !$price || !$description) {
    $msg->setErr('i18n:invalid_query');
    header('Location: /listings/new', true, 303);
    die;
}

$listing = (new App\Builder\ListingBuilder())->make();

try {
    $listing->create($title, $price, $description, $_FILES);
    $msg->setOk('i18n:offer_created');
    header('Location: /profile/listings', true, 303);
} catch (\InvalidArgumentException $e) {
    $msg->fromException($e);
    header('Location: /listings/new', true, 303);
}

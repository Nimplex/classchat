<?php

/** @var \App\Controller\UserController $user_controller */
global $user_controller;

$current_user_id = $_SESSION['user_id'];
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$listing_id = filter_input(INPUT_POST, 'listing_id', FILTER_VALIDATE_INT);
$reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_SPECIAL_CHARS);
$reason_len = str_len($reason);

if (!isset($user_id) || !isset($reason) || $reason_len <= 0 || $reason_len > 255) {
    // @todo: implement correct error handling.
    echo '0';
    die;
}

$user_controller->reports->create($current_user_id, $user_id, $listing_id, $reason);

echo '1';
die;

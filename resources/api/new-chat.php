<?php

use App\FlashMessage;

/** @var \App\Controller\UserController $user_controller */
global $user_controller, $_ROUTE;

$listing_model = (new App\Builder\ListingBuilder())->make();
$chats_model = (new App\Builder\ChatsBuilder())->make();

$current_user_id = $_SESSION['user_id'];
$content = $_POST['content'] ?: null;
$listing_id = $_POST['listing_id'] ?: null;
$user_id = $_POST['user_id'] ?: null;

$listing = null;

if ((!isset($listing_id) && !isset($user_id)) || !isset($content)) {
    (new FlashMessage())->setErr('i18n:invalid_query_parameters');
    header('Location: /messages', true, 303);
    die;
}

if (strlen($content) == 0) {
    (new FlashMessage())->setErr('i18n:empty_content');
    header('Location: /messages', true, 303);
    die;
}

if (isset($listing_id)) {
    $listing = $listing_model->get($listing_id, $_SESSION['user_id']);
    if (!$listing) {
        (new FlashMessage())->setErr('i18n:listing_not_found');
        header('Location: /messages', true, 303);
        die;
    }
}

if (isset($user_id)) {
    $exists = $user_controller->user->find_by_id($user_id);
    if (!$exists) {
        (new FlashMessage())->setErr('i18n:user_not_found');
        header('Location: /messages', true, 303);
        die;
    }
}

$seller_id = $listing_id ? $listing['user_id'] : $user_id;

if ($listing_id) {
    $res = $chats_model->find_listings($seller_id, $current_user_id, $listing_id);
    if ($res) {
        header("Location: /messages/{$res['id']}", true, 303);
        die;
    }
} elseif ($user_id) {
    $res = $chats_model->find_standalone($seller_id, $current_user_id);
    if ($res) {
        header("Location: /messages/{$res['id']}", true, 303);
        die;
    }
}

$chat_id = $chats_model->create($seller_id, $current_user_id, $listing_id);

// == false because ! will also match ID 0
if ($chat_id == false) {
    (new FlashMessage())->setErr('i18n:database_fail');
    // todo: proper error handling, inform user about database error
    header('Location: /messages', true, 303);
    die;
}

$message_id = $chats_model->add_message($chat_id, $current_user_id, $content);

header("Location: /messages/{$chat_id}", true, 303);

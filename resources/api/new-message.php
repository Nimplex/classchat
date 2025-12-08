<?php

use App\FlashMessage;

/** @var \App\Controller\UserController $user_controller */
global $user_controller, $_ROUTE;

$chats_model = (new App\Builder\ChatsBuilder())->make();

$current_user_id = $_SESSION['user_id'];
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
$chat_id = filter_input(INPUT_POST, 'chat_id', FILTER_VALIDATE_INT);

if (!isset($content) || !isset($chat_id)) {
    (new FlashMessage())->setErr('i18n:invalid_query_parameters');
    header('Location: /messages', true, 303);
    die;
}

if (empty($content)) {
    (new FlashMessage())->setErr('i18n:empty_content');
    header('Location: /messages', true, 303);
    die;
}

$chat = $chats_model->find_by_id($chat_id);

if (!isset($chat)) {
    (new FlashMessage())->setErr('i18n:chat_does_not_exist');
    header('Location: /messages', true, 303);
    die;
}

$res = $chats_model->add_message($chat_id, $current_user_id, $content);

if ($res == false) {
    (new FlashMessage())->setErr('i18n:database_fail');
}

header("Location: /messages/{$chat_id}", true, 303);

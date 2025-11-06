<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use App\FlashMessage;

/** @var \App\Controller\UserController $user */
global $user;

try {
    $user->register_from_request($_POST);
    (new FlashMessage())->setOk('Rejestracja udana! Wysłano kod aktywacyjny na skrzynke e-mail');
    header('Location: /login.php', true, 303);
} catch (\InvalidArgumentException $e) {
    (new FlashMessage())->fromException($e);
    header('Location: /register.php', true, 303);
}

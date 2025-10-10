<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use App\FlashMessage;

/** @var \App\Model\Auth $auth */
global $auth;

try {
    $auth->login_from_request($_POST);
    echo 'toilet';
    header('Location: /?login=1', true, 303);
} catch (\InvalidArgumentException $e) {
    (new FlashMessage())->fromException($e);
    header('Location: /login.php', true, 303);
}

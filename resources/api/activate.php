<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use App\FlashMessage;

/** @var \App\Model\Auth $auth */
global $auth;
global $_ROUTE;

try {
    $auth->activate_from_request($_ROUTE);
    (new FlashMessage())->setOk('Konto zostało aktywowane!');
    header('Location: /login.php', true, 303);
} catch (\InvalidArgumentException $e) {
    (new FlashMessage())->fromException($e);
    var_dump($e);
    die;
    header('Location: /', true, 303);
}

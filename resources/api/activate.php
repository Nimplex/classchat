<?php

use App\FlashMessage;

/** @var \App\Controller\UserController $user_controller */
global $user_controller, $_ROUTE;

try {
    $user_controller->activate_from_request($_ROUTE);
    (new FlashMessage())->setOk('Konto zostało aktywowane!');
    header('Location: /login', true, 303);
} catch (\InvalidArgumentException $e) {
    (new FlashMessage())->fromException($e);
    header('Location: /', true, 303);
}

<?php

use App\FlashMessage;

/** @var \App\Controller\UserController $user_controller */
global $user_controller;

try {
    $user_controller->login_from_request($_POST);
    header('Location: /?login=1', true, 303);
} catch (\InvalidArgumentException $e) {
    (new FlashMessage())->fromException($e);
    header('Location: /login', true, 303);
}

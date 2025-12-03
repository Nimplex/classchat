<?php

/** @var \App\Controller\UserController $user_controller */
global $user_controller;

try {
    echo $user_controller->favourite_from_request($_POST) ? "yes" : "no";
} catch (\InvalidArgumentException $e) {
    http_response_code(500);
}

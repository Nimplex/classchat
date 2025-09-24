<?php
session_start();

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Model\User;
use App\Service\AuthService;
use App\Controller\AuthController;

$userModel = new User($db);
$authService = new AuthService($userModel);
$authController = new AuthController($authService);

$path = $_SERVER['PATH_INFO'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/register' && $method === 'POST') {
    $res = $authController->register($_POST);
    echo $res;
} elseif ($path === '/login' && $method === 'POST') {
    $res = $authController->login($_POST);
    echo $res;
} else {
    http_response_code(404);
    echo 'Not found';
}

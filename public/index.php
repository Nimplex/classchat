<?php

session_start();

/** @var PDO $db */
require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/router.php';

use App\Model\Auth;

$auth = new Auth($db);
$router = new Router();

$router->GET("/", function (array $query, array $body) {
    echo $query;
    echo 'home page???<br>';
    echo 'is logged in: ' . (isset($_SESSION['user_id']) ? 'true' : 'false');
});

$router->POST('/api/register', function (array $query, array $body) use ($auth) {
    $res = $auth->register_from_request($body);
    echo $res;
});

$router->POST('/api/login', function (array $query, array $body) use ($auth) {
    $res = $auth->login_from_request($body);
    echo $res;
});

$router->POST('/api/logout', function (array $query, array $body) {
    session_destroy();
    echo 'Logged out';
});

$router->POST('/api/new-listing', function (array $query, array $body) {
    include __DIR__ . '/../resources/api/new-listing.php';
});

$router->ERROR('404', function () {
    include __DIR__ . '/404.php';
});

$router->handle();

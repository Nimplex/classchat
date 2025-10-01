<?php
@session_start();
if (!isset($_SESSION['user_id']) || !isset($_SERVER['HTTP_HX_REQUEST'])) {
    require $_SERVER['DOCUMENT_ROOT'] . '/403.php';
    die;
}

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
$listing = (new App\Builder\ListingBuilder())->make();

$page = $_GET['page'] ?: 1;

var_dump($listing->listAll($page));

?>



<?php

$path = $_SERVER['PATH_INFO'] ?? '/';

// this just trims the rest of the url so that navigating to
// `/foo/bar/baz` won't show `/foo/bar/index.php` if there's no `/foo/bar/baz/index.php`
if ($path != "/") {
    require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
    die;
}

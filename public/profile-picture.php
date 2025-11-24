<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/check-auth.php';

$filename = $_GET['file'] ?? '';
$baseDir = realpath($_SERVER['DOCUMENT_ROOT'] . '/../storage/profile-pictures') . DIRECTORY_SEPARATOR;

$filepath = realpath($baseDir . $filename);

if (!$filepath || !str_starts_with($filepath, $baseDir) || !is_file($filepath)) {
    require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
    die;
}

$mime = mime_content_type($filepath);
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filepath));

readfile($filepath);
die;

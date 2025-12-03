<?php

http_response_code(404);

$TITLE = '404';
$HEAD = '<link rel="stylesheet" href="/_dist/css/error.css">';

$CONTENT = <<<HTML
<h1>Not found</h1>
HTML;

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

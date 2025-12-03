<?php

http_response_code(403);

$TITLE = '403';
$HEAD = '<link rel="stylesheet" href="/_dist/css/error.css">';

$CONTENT = <<<HTML
<h1>Forbidden</h1>
HTML;

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

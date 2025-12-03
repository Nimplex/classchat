<?php

http_response_code(401);

$TITLE = '401';
$HEAD = '<link rel="stylesheet" href="/_dist/css/error.css">';

$CONTENT = <<<HTML
<h1>Unauthorized</h1>
HTML;

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

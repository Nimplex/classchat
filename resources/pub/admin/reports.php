<?php

if (isset($_SERVER['HTTP_PARTIAL_REQ'])) {
    require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/templates/reports.php';
    die;
}

/** @var \App\Controller\UserController $user_controller */
global $user_controller;

$TITLE = 'Zgłoszenia';
$HEAD = '<link rel="stylesheet" href="/_dist/css/reports.css">';

ob_start();
?>

<h1>Zgłoszenia</h1>
<hr>
<div id="reports">
    <?php require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/templates/reports.php'; ?>
</div>

<?php
$CONTENT = ob_get_clean();

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

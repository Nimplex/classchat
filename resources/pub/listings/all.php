<?php

if (isset($_SERVER['HTTP_PARTIAL_REQ'])) {
    require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/templates/listings.php';
    die;
}

$lis = (new App\Builder\ListingBuilder())->make();

$title = 'Ogłoszenia';

$render_head = function () {
    return <<<HTML
    <link rel="stylesheet" href="/_dist/css/all_listings.css">
    <noscript>
        <style>
            #throbber { display: none; }
        </style>
    </noscript>
    HTML;
};

$total_pages = $lis->countPages();

ob_start();
?>

<div id="heading">
    <h1>Aktualne ogłoszenia</h1>
    <form action="/listings/new" method="GET">
        <button class="btn-accent" type="submit">
            <i data-lucide="package-plus" aria-hidden="true"></i>
            Nowe ogłoszenie
        </button>
    </form>
</div>
<hr>
<div id="offers">
    <?php require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/templates/listings.php'; ?>
</div>

<?php
$render_content = ob_get_clean();

$render_scripts = function () {
    return <<<HTML
    <script type="module" src="/_dist/js/listings.js"></script>
    <script type="module" src="/_dist/js/scroll.js"></script>
    HTML;
};

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

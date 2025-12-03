<?php

$listing_model = (new App\Builder\ListingBuilder())->make();

$title = 'Oferty użytkownika ' . htmlspecialchars($_SESSION['user_display_name']);

ob_start();
?>

<h1>Moje oferty</h1>
<div>
    <?php foreach ($listing_model->listByUser($_SESSION['user_id']) as $listing): ?>
    <div class="listing">
        <div class="listing-title"><?= $listing['title'] ?></div>
        <div class="listing-price"><?= $listing['price'] ?></div>
        <div class="listing-timestamp"><?= $listing['updated_at'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<?php
$render_content = ob_get_clean();

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

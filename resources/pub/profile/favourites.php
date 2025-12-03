<?php

$favourites_model = (new App\Builder\FavouritesBuilder())->make();

$title = "Polubione";

ob_start();
?>

<h1>Polubione</h1>
<div>
    <?php foreach ($favourites_model->find_by_user_id($_SESSION['user_id']) as $listing): ?>
    <div class="listing">
        <div class="listing-title"><?= $listing['title'] ?></div>
        <div class="listing-price"><?= $listing['price'] ?></div>
        <div class="listing-timestamp"><?= $listing['updated_at'] ?></div>
        <div class="listing-active"><?= $listing['active'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<?php
$render_content = ob_get_clean();

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

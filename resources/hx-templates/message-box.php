<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

$msg = new App\FlashMessage();
if (!$msg->exists()) {
    return;
}
?>

<div id="<?= $msg->getType()->name; ?>">
    <?= $msg->getMsg(); ?>
</div>

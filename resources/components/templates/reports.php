<?php

use App\Helper\DateHelper;

/** @var \App\Controller\UserController $user_controller */
global $user_controller;

$page = max(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1, 1);
$is_partial = $_SERVER['HTTP_PARTIAL_REQ'] ?? null;
?>

<?php foreach ($user_controller->reports->get_all($page) as $report): ?>
<a href="/admin/reports/<?= $report['id'] ?>">
    <ul class="report">
        <li>#<?= $report['id'] ?></li>
        <li>Zgłaszający: <?= htmlspecialchars($report['reporter_name']) . " ({$report['reporter_id']})" ?></li>
        <li>Zgłaszany: <?= htmlspecialchars($report['reported_name']) . " ({$report['reported_id']})" ?></li>
        <li>Podłączone ogłoszenie: <?= $report['contains_listing'] ? 'Tak' : 'Nie' ?></li>
        <li>Data zgłoszenia: <?= DateHelper::relative($report['created_at']) ?></li>
        <li>Powód: <?= htmlspecialchars($report['reason']) ?></li>
    </ul>
</a>
<?php endforeach ?>

<?php if (!empty($user_controller->reports->get_all($page + 1))): ?>
<div id="sentinel" data-next-page="<?= $page + 1 ?>"></div>
<div id="throbber" aria-hidden="true">Wczytywanie...</div>
<?php endif; ?>

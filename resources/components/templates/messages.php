<?php

/** @var \App\Builder\ChatBuilders $chats_model */
/** @var array<string, string> $chat */

$page = max(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1, 1);
$is_partial = isset($_SERVER['HTTP_PARTIAL_REQ']);
?>

<?php foreach ($chats_model->get_messages($chat['chat_id'], $page) as $message): ?>
<div class="message <?= $_SESSION['user_id'] == $message['user_id'] ? 'author' : '' ?> <?= $is_partial ? 'settling' : '' ?>">
    <p class="message-author"><?= htmlspecialchars($message['display_name']) ?></p>
    <p class="message-content"><?= htmlspecialchars($message['content']) ?></p>
    <svg xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 12 12">
        <path d="M 12 12 L 0 12 L 0 0 C 1.328125 8.300781 3.167969 10.355469 12 12 Z M 12 12 "/>
    </svg>
</div>
<?php endforeach ?>

<?php if (!empty($chats_model->get_messages($chat['chat_id'], $page + 1))): ?>
<div id="sentinel" data-next-page="<?= $page + 1 ?>" data-chat-id="<?= $chat['chat_id'] ?>" role="status" aria-live="polite" aria-label="Ładowanie kolejnych wiadomości"></div>
<div id="throbber" aria-hidden="true">Wczytywanie...</div>
<?php endif; ?>

<p>Witaj <b><?= htmlspecialchars($name) ?></b>,</p>
<p>Twój link aktywacyjny: <a href="<?= htmlspecialchars(urlencode($link)) ?>">Kliknij tutaj</a></p>
<p>(Jeśli z jakiegokolwiek powodu nie możesz nacisnąć przycisku, podążaj za tym łączem: <?= $link ?>)</p>

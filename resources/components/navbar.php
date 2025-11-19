<?php
$user_item = "";

if (!isset($_SESSION['user_id'])) {
    $user_item .= <<<HTML
    <li>
        <a href="/login.php">Zaloguj się</a>
    </li>
    HTML;
} else {
    $user_item .= <<<HTML
    <li>
        <a href="/listings/all.php">
            <i data-lucide="badge-euro"></i>
            Ogłoszenia
        </a>
    </li>
    <li>
        <a href="/profile/@favourites.php">
            <i data-lucide="star"></i>
            Polubione
        </a>
    </li>
    <li>
        <a href="/profile/@me.php">
            <i data-lucide="user"></i>
            Witaj {$_SESSION['user_login']}
        </a>
    </li>
    HTML;
}
?>

<nav aria-label="Main navigation">
    <div role="presentation" class="nav-inner">
        <ul class="desktop">
            <li><a href="/"><img src="/_assets/thumbnail.png" height="40"></a></li>
        </ul>
        <form class="inline-input" role="search" action="/listings/search.php" method="get">
            <input type="search" id="site-search" name="q" placeholder="Wyszukaj...">
            <button type="submit" aria-label="Wyszukaj">
                <i data-lucide="search" aria-hidden="true"></i>
            </button>
        </form>
        <ul class="desktop"><?= $user_item ?></ul>
        <button id="menu-toggle" class="mobile" aria-expanded="false" aria-controls="mobile-container">≡</button>
    </div>
    <ul id="mobile-container" hidden>
        <li><a href="/">Strona główna</a></li>
        <?= $user_item ?>
    </ul>
</nav>

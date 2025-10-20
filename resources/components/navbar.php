<nav aria-label="Main navigation">
    <a href="/">Strona główna</a> 
    <a href="/listings/all.php">Ogłoszenia</a>

    <?php
    @session_start();
    if (!isset($_SESSION['user_id'])) {
        echo '<a href="/login.php">Zaloguj się</a>';
    } else {
        echo "<a href=\"/profile/@me\">Witaj {$_SESSION['user_login']}</a>";
    }
    ?>
</nav>

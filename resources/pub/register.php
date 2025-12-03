<?php

$pat = App\Controller\UserController::PASSWORD_PATTERN;
$new_pat = substr($pat, 2, strlen($pat) - 4);

$title = 'Rejestracja';
$no_navbar = true;

$render_head = function (): string {
    return <<<HTML
    <link rel="stylesheet" href="/_dist/css/register.css">
    HTML;
};

ob_start();

?>

<form action="/api/register" method="POST">
    <h1>Zarejestruj się</h1>
    <label>Nazwa użytkownika: <input type="text" name="display_name" placeholder="Nazwa wyświetlana" maxlength="255" required></label><br>
    <label>Login: <input type="text" name="login" placeholder="Login" maxlength="255" required></label><br>
    <label>E-mail: <input type="email" name="email" placeholder="E-mail" maxlength="255" required></label><br>
    <label>Hasło: <input id="pass_input" type="password" name="password" placeholder="Hasło" minlength="8" pattern="{$new_pat}" required></label>
    <div class="req">
        Hasło musi zawierać:
        <ul>
            <li id="pass_l">Min. 1 mała litera (a-z)</li>
            <li id="pass_u">Min. 1 wielka litera (A-Z)</li>
            <li id="pass_n">Min. 1 cyfra (0-9)</li>
            <li id="pass_s">Min. 1 znak specjalny (@, $, !, %, *, ?, &)</li>
            <li id="pass_8">8 lub więcej ww. znaków</li>
        </ul>
        <br>
    </div>
    <input type="submit" value="Zarejestruj się">
    <footer role="contentinfo">
        <p class="small-text">
            Korzystając z serwisu, akceptujesz
            <a href="/terms-of-use">Regulamin</a> i
            <a href="/privacy-policy">Politykę prywatności</a>.
        </p>
    </footer>
</form>

<?php

$render_content = ob_get_clean();

$render_scripts = function (): string {
    return <<<HTML
    <script type="module" src="/_dist/js/register.js"></script>
    HTML;
};

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

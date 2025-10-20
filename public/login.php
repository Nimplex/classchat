<?php

$title = 'Logowanie';

function render_head()
{
    return <<<HTML
    <link rel="stylesheet" href="/_css/login.css">
    HTML;
}

function render_content()
{
    return <<<HTML
    <form action="/api/login" method="POST">
        <label>E-mail: <input type="email" name="email" required></label><br>
        <label>Hasło: <input type="password" name="password" minlength="8" required></label><br>
        <input type="submit" value="Zaloguj się">
    </form>
HTML;
}

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

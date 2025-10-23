<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/check-auth.php';

$title = "Nowe ogłoszenie";

function render_content()
{
    return <<<HTML
    <h1>Nowe ogłoszenie</h1>
    <hr>
    <div>
        <form action="/api/new-listing" method="POST">
            <label>
                Tytuł ogłoszenia:
                <input type="text" name="title" minlength="8" maxlength="100" required>
            </label>
            <br>
            <label>
                Opis:
                <textarea name="description" minlength="20" maxlength="1000" required></textarea>
            </label>
            <br>
            <div class="row">
                <div>
                    <label>
                        Cena:<br>
                        <input
                            class="money-input"
                            type="text"
                            inputmode="numeric"
                            pattern="\d{,4}((,|\.)\d\d)?"
                            name="price"
                            placeholder="5,00"
                            required
                        >
                        <span>zł</span>
                    </label>
                </div>
                <div id="image-input-outer">
                    <h3>Zdjęcia</h3>
                    <div id="image-input">
                        <img>
                        <label>
                            +
                            <input type="file" class="sr-only">
                        </label>
                    </div>
                </div>
            </div>
            <br>
            <input type="submit" value="Stwórz">
        </form>
    </div>
    HTML;
}

function render_head()
{
    return <<<HTML
        <link rel="stylesheet" href="/_css/new.css">
    HTML;
}

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

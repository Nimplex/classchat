<?php

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/check-auth.php';

$title = "Nowe ogłoszenie";

function render_scripts(): string
{
    return <<<'HTML'
    <script>
        function previewFile(input) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onloadend = () => {
                const label = input.parentNode;
                const wrapper = label.parentNode;
                const grid = document.getElementById('image-input');
                const img = document.createElement('img');
                label.replaceChild(img, label.firstChild);

                img.src = reader.result;

                if (wrapper.className === "has-img")
                    return;

                // new input field
                const new_field = document.createElement('div');
                new_field.innerHTML = `
                    <label>
                        +
                        <input
                            type="file"
                            class="sr-only"
                            onchange="previewFile(this)"
                            name="image${[...grid.children].indexOf(wrapper) + 1}"
                        >
                    </label>
                    <div onclick="removeFile(this)">×</div>`;
                grid.insertBefore(new_field, wrapper.nextSibling);
                wrapper.className = "has-img";
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                img.src = "";
            }
        }
        
        function removeFile(close) {
            const wrapper = close.parentNode;
            const grid = document.getElementById('image-input');

            wrapper.remove();

            const field_amount = grid.children.length - 1;
            for (let i = 0; i < field_amount; i++) {
                grid.children[i].firstElementChild.children[1].name = `image${i}`;
            }
        }
    </script>
    HTML;
}

function render_content(): string
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
                    <br><br>
                </div>
                <div id="image-input-outer">
                    <h3>Zdjęcia</h3>
                    <br>
                    <div id="image-input">
                        <div>
                            <label>
                                +
                                <input
                                    type="file"
                                    class="sr-only"
                                    onchange="previewFile(this)"
                                    name="image0"
                                >
                            </label>
                            <div onclick="removeFile(this)">×</div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <input type="submit" value="Stwórz">
        </form>
    </div>
    HTML;
}

function render_head(): string
{
    return <<<HTML
        <link rel="stylesheet" href="/_css/new.css">
    HTML;
}

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/container.php';

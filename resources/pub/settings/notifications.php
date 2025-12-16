<?php

/** @var \App\Controller\UserController $user_controller */
global $user_controller;

$SETTINGS_PAGE = [
    'self-url' => '/settings/notifications',
    'head' => '<link rel="stylesheet" href="/_dist/css/settings/notifications.css">',
    'title' => 'Ustawienia powiadomień',
    'scripts' => [],
];

ob_start();
?>

<form action="/api/update-notifications" method="POST" enctype="multipart/form-data">
    <table>
        <tbody>
            <tr>
                <td>Powiadomienia o przychodzących wiadomościach</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_message">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia dotyczące zgłoszeń (werdykty, przyjęcie)</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_reports">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia o logowaniu</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_login">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia o ogłoszeniach (dodawanie)</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_listings">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia administracyjne (ważne powiadomienia)</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_administrative">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Ogłoszenia ogólne</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_contact">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia marketingowe</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="notifications_marketing">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia push (aplikacja)</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="mobile_app_notifications">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Powiadomienia mailowe</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="email_notifications">
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="btn-accent">
        <i data-lucide="save" aria-hidden="true"></i>
        <span>Zapisz zmiany</span>
    </button>
</form>

<?php
$CONTENT = ob_get_clean();

require $_SERVER['DOCUMENT_ROOT'] . '/../resources/components/settings.php';


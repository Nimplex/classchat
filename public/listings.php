<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /403.php');
    die;
}
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oferty</title>
</head>
<body>
    <h1>Aktualne oferty</h1>
    <?php

    echo "a";

    ?>
</body>

</html>

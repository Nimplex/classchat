<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szopex – Aktywacja konta</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #f6f6f6;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #000000;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        .header img {
            max-width: 200px;
            height: auto;
        }
        .content {
            padding: 20px;
            line-height: 1.5;
        }
        .content p {
            margin: 0 0 15px 0;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="https://szopex.nimplex.xyz/_assets/logo.png" alt="Szopex Logo">
    </div>

    <div class="content">
        <p>Witaj <strong><?= htmlspecialchars($name) ?></strong>,</p>

        <p>
            Otrzymujesz tę wiadomość, ponieważ rozpocząłeś proces rejestracji w serwisie Szopex.
            Aby aktywować swoje konto, skorzystaj z poniższego przycisku.
        </p>

        <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:20px;">
            <tr>
                <td align="center" bgcolor="#1a73e8" style="border-radius:4px;">
                    <a href="<?= $link ?>"
                       style="display:inline-block; padding:12px 22px; font-size:16px; color:#ffffff; text-decoration:none; font-weight:bold;">
                        Aktywuj konto
                    </a>
                </td>
            </tr>
        </table>

        <p style="margin-top:20px;">
            Jeżeli przycisk nie działa, możesz użyć tego linku:<br>
            <a href="<?= $link ?>"><?= $link ?></a>
        </p>

        <p style="margin-top:20px; font-weight:bold;">
            Link aktywacyjny jest ważny przez 15 minut.
        </p>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> Szopex. Wszelkie prawa zastrzeżone.</p>
    </div>
</div>

</body>
</html>

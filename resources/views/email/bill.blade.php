<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Facture</title>
</head>

<body>
    <p>Bonjour {{ $name }},</p>
    <p>Veuillez trouver ci-joint votre facture.</p>
    <p>Merci pour votre confiance.</p>
    <p>Cordialement,<br />&nbsp;<br />{{ config('app.name') }}</p>
</body>

</html>

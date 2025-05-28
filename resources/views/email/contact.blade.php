<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulaire de contact</title>
</head>
<body>
    <h1>Bonjour,</h1>
    <p>Bonne nouvelle ! Vous avez reçu un message depuis le formulaire de contact de votre site.</p>
    <h2>Expéditeur : {{ $fullname}} < {{ $email}} ></h2>
    <p>Message : {{ $content }}</p>
</body>
</html>


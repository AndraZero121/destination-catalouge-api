<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>OTP Code</title>
    </head>
    <body>
        <p>Here is your OTP code for {{ $label }}:</p>
        <h2>{{ $code }}</h2>
        <p>This code expires in {{ $expiresMinutes }} minutes.</p>
        <p>If you did not request this, please ignore this email.</p>
    </body>
</html>

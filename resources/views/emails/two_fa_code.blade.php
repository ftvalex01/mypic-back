<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido a MyPic - Código de Verificación 2FA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        strong {
            color: #333;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #009688;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a MyPic</h1>
        <p>Nos alegra tenerte como parte de nuestra comunidad. Estamos emocionados de empezar este viaje contigo.</p>
        <p>Por favor, utiliza el siguiente código de verificación en dos pasos (2FA) para completar tu registro:</p>
        <p><strong class="code">{{ $code }}</strong></p>
        <p>Este código es válido por 10 minutos.</p>
    </div>
</body>
</html>

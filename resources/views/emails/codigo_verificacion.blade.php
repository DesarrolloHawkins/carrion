<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .email-body {
            font-size: 18px;
            line-height: 1.5;
            color: #555;
        }
        .codigo {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            margin: 20px 0;
        }
        .email-footer {
            font-size: 14px;
            color: #888;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Código de Verificación
        </div>
        <div class="email-body">
            <p>Hola,</p>
            <p>Hemos recibido una solicitud para restablecer tu contraseña. Usa el siguiente código de verificación para continuar con el proceso:</p>
            <div class="codigo">{{ $codigo }}</div>
            <p>Si no solicitaste este código, por favor, ignora este correo.</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Union de Hermandades de Jerez. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>

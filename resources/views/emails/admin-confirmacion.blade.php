<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirmar cambio de contraseña</title>
    <style>

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0; padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 {
            color: #2d89ef;
            font-weight: 700;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }
        .btn-confirm {
            background-color: #2d89ef;
            color: white !important;
            padding: 12px 24px;
            border-radius: 6px;
            display: inline-block;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            font-size: 13px;
            color: #888;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 15px;
            text-align: center;
        }
        @media only screen and (max-width: 480px) {
            .container {
                margin: 15px;
                padding: 20px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hola {{ auth()->user()->persona->Nombre }},</h1>

        <p>Recibimos una solicitud para cambiar la contraseña de administrador asociada a este correo.</p>

        <p>Para confirmar el cambio y generar una nueva contraseña, haz clic en el siguiente botón:</p>

        <p>
            <a href="{{ $url }}" class="btn-confirm" target="_blank" rel="noopener noreferrer">
                Confirmar cambio de contraseña
            </a>
        </p>

        <p>Este enlace estará disponible por 60 minutos.</p>

        <p>Si no realizaste esta solicitud, puedes ignorar este mensaje.</p>

        <p>Gracias,<br>El equipo de administración</p>

        <div class="footer">
            &copy; {{ date('Y') }} Desarrollo Web. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>

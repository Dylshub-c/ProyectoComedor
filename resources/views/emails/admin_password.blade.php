<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecimiento de Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
  <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 25px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
    
    <h2 style="color: #0d6efd; text-align: center; margin-bottom: 20px;">Restablecimiento de Contraseña</h2>

    <p style="font-size: 16px; color: #333;">
      Has solicitado restablecer la contraseña de tu cuenta de administrador del <strong>Sistema de Comedor</strong>.
    </p>

    <div style="background-color: #f1f8ff; padding: 15px 20px; border-left: 5px solid #0d6efd; border-radius: 5px; margin: 20px 0;">
      <p style="margin: 0; font-size: 15px;"><strong>Correo:</strong> {{ $email }}</p>
      <p style="margin: 0; font-size: 15px;"><strong>Nueva contraseña:</strong> {{ $password }}</p>
    </div>

    <p style="font-size: 15px; color: #555;">
      Por favor, inicia sesión con esta contraseña.
    </p>

    <p style="font-size: 14px; color: #888;">
      Si no realizaste esta solicitud, ignora este mensaje o contacta al administrador del sistema.
    </p>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

    <p style="font-size: 12px; text-align: center; color: #aaa;">
      Este es un mensaje automático del Sistema de Comedor. No respondas a este correo.
    </p>
  </div>
</body>
</html>

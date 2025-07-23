<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bienvenida Orientadora</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="background-color: #f8f9fa; padding: 20px; font-family: Arial, sans-serif;">
  <div class="container p-4" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 class="text-primary mb-4">¡Bienvenido, Orientadora!</h2>

    <p>Has sido registrado como administradora del <strong>Sistema de Comedor</strong>.</p>

    <div class="p-3 my-4" style="background-color: #e9f5ff; border-left: 5px solid #0d6efd; border-radius: 4px;">
      <p class="mb-2"><strong>Correo de acceso:</strong> <span class="text-dark">{{ $email }}</span></p>
      <p class="mb-0"><strong>Contraseña asignada:</strong> <span class="text-dark">{{ $password }}</span></p>
    </div>

    <p>Gracias por formar parte del equipo.</p>

    <hr />

    <p style="font-size: 0.85rem; color: #6c757d;">Este es un mensaje automático, por favor no respondas a este correo.</p>
  </div>
</body>
</html>

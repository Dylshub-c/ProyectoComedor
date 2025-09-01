<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SICAB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="/img/LogoDW-Negro.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/img/LogoDW-Blanco.png" media="(prefers-color-scheme: dark)">
</head>
<body>
      <div class="login-container text-center">
        <img src="{{ asset('img/LogoCovao.webp') }}" alt="Logo" class="logo" />
        <h5>Inicio de Sesión</h5>

        @if(session('error'))
          <div class="custom-alert alert-error">
            {{ session('error') }}
          </div>
        @endif

        <form id="formularioLogin" method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="email" class="form-control" name="email" id="correo" placeholder="Ingrese su correo" required>
            </div>
            @error('email')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-2 text-start">
            <label class="form-label" for="password">Contraseña:</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su contraseña" required>
            </div>
            @error('password')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="recordar" />
              <label class="form-check-label" for="recordar">Recordarme</label>
            </div>
            <a href="{{ route('admin.password.request') }}">¿Olvidó su contraseña?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
      </div>
</body>
</html>

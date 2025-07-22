<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Recuperar Contraseña</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/OlvidarContrasena.css') }}">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="recuperar-container">
            <h5 class="text-center mb-4">Recuperar contraseña</h5>

            <form id="formRecuperar" method="POST" action="{{ route('admin.password.reset') }}">
                @csrf
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo" name="email" placeholder="ejemplo@correo.com" required/>
                </div>
                <button id="btnEnviar" type="submit" class="btn btn-primary w-100">Enviar instrucciones</button>
            </form>

            @if(session('status'))
                <div id="mensaje" class="mt-3 text-success text-center">
                    ✔️ {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/OlvidarContrasena.js') }}"></script>
</body>
</html>


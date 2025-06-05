<html>
<body>
    <h2>¿Olvidaste tu contraseña de administrador?</h2>

    @if(session('status'))
        <p style="color: green">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.password.reset') }}">
        @csrf
        <label>Correo electrónico:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Enviar nueva contraseña</button>
    </form>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>

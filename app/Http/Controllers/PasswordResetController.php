<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminPasswordMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use App\Mail\ConfirmarNuevaContrasena;


class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.admin-forgot-password');
    }

    public function reset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::with('persona')->where('email', $request->email)->first();

        if (!$user || $user->persona->TipoUsuario !== 'admin') {
            return back()->withErrors(['email' => 'Este correo no pertenece a un administrador.']);
        }

        // Limitar a 3 intentos (igual que antes)
        $cacheKey = 'admin_reset_attempts:' . $user->email;
        $attempts = Cache::get($cacheKey, 0);

        if ($attempts >= 3) {
            return back()->withErrors(['email' => 'Has alcanzado el límite de 3 restablecimientos por hora.']);
        }

        // un enlace firmado que expira en 60 minutos
        $url = URL::temporarySignedRoute(
            'admin.password.confirm', now()->addMinutes(60), ['email' => $user->email]
        );

        // Obtener el nombre para el correo
        $nombre = $user->persona->Nombre ?? $user->name ?? 'Usuario';

        // Enviar correo con el enlace (sin cambiar contraseña todavía)
        Mail::to($user->email)->send(new ConfirmarNuevaContrasena($user, $url, $nombre));

        // Incrementar intentos
        Cache::put($cacheKey, $attempts + 1, now()->addHour());

        return back()->with('status', 'Se ha enviado un enlace de confirmación a tu correo.');
    }


    public function confirmReset(Request $request)
    {
        $email = $request->query('email');

        $user = User::where('email', $email)->first();

        if (!$user || $user->persona->TipoUsuario !== 'admin') {
            abort(403, 'No autorizado');
        }

        // Generar nueva contraseña
        $newPassword = Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->save();

        // Enviar la nueva contraseña al correo
        Mail::to($user->email)->send(new AdminPasswordMail($user->email, $newPassword));

        return view('auth.cambio-contra');
    }

}

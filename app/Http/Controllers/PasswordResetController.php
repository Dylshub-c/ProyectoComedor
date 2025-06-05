<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminPasswordMail;


class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.admin-forgot-password');
    }

    public function reset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->persona->TipoUsuario !== 'admin') {
             return back()->withErrors(['email' => 'Este correo no pertenece a un administrador.']);
        }

        $newPassword = Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->save();

        Mail::to($user->email)->send(new AdminPasswordMail(
            $user->email,
            $newPassword
        ));

        return back()->with('status', 'Se ha enviado una nueva contraseña a tu correo.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordMail;
use App\Mail\AdminRegisteredMail;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $personaData = [
        'Nombre' => 'Natalia',
        'PrimerApellido' => 'Martinez',
        'SegundoApellido' => 'Uribe',
        'Cedula' => '78938234',
        'TipoUsuario' => 'admin',
        ];

        // Crear o conseguir la persona (evitar duplicados por cédula, por ejemplo)
        $persona = Persona::firstOrCreate(
            ['Cedula' => $personaData['Cedula']],
            $personaData
        );

        $email = 'uwu@gmail.com';

        // Generar una contraseña predeterminada o aleatoria
        $password = Str::random(10);

        // Crear o conseguir el usuario asociado a esa persona
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'persona_id' => $persona->id,
                'password' => bcrypt($password),
            ]
        );

        // Si se creó el usuario (no existía antes), enviar correo con la contraseña
        if ($user->wasRecentlyCreated) {
            Mail::to($user->email)->send(new \App\Mail\AdminRegisteredMail($email, $password, $persona->Nombre));

        }
        }
}

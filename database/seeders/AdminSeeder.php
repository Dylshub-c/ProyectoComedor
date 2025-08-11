<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use App\Models\Encargado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminRegisteredMail;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {

        Role::firstOrCreate(['name' => 'Administrador']);

        // Datos del admin
        $personaData = [
            'Nombre' => 'Natalia',
            'PrimerApellido' => 'Martinez',
            'SegundoApellido' => 'Uribe',
            'Cedula' => '78938234',
            'TipoUsuario' => 'admin', // siempre admin en este seeder
        ];

        // Crear o conseguir la persona
        $persona = Persona::firstOrCreate(
            ['Cedula' => $personaData['Cedula']],
            $personaData
        );

        $email = 'dylanperira0204@gmail.com';
        $password = Str::random(10);

        // Crear o conseguir el usuario
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'persona_id' => $persona->id,
                'password' => bcrypt($password),
            ]
        );

        // Asignar rol de administrador
        $user->assignRole('Administrador');

        // Si es nuevo, enviar correo y registrar en encargado
        if ($user->wasRecentlyCreated) {
            Mail::to($user->email)->send(
                new AdminRegisteredMail($email, $password, $persona->Nombre)
            );

            Encargado::create([
                'persona_id' => $persona->id,
                'correo' => $email,
            ]);
        }
    }
}

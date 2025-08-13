<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Ingreso comedor
            'ver ingreso comedor',

            // Estudiantes
            'ver estudiantes',
            'crear estudiantes',
            'editar estudiantes',
            'eliminar estudiantes',
            'importar estudiantes',

            // Tipo beca
            'ver tipo beca',
            'crear tipo beca',
            'editar tipo beca',
            'eliminar tipo beca',

            // Fotos
            'subir fotos',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $rolAdmin = Role::firstOrCreate(['name' => 'administrador']);
        $rolAdmin->syncPermissions($permissions);

        $user = User::find(1);
        if ($user) {
            $user->assignRole('administrador');
        }
    }
}

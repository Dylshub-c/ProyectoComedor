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
            // Ingreso al comedor / Asistencia
            'ver ingreso comedor',
            'asistencia rápida',
            'ver asistencia',
            'registrar asistencia',

            // Estudiantes
            'ver estudiantes',
            'crear estudiantes',
            'editar estudiantes',
            'eliminar estudiantes',
            'importar estudiantes',

            // Tipo Beca
            'ver tipo beca',
            'crear tipo beca',
            'editar tipo beca',
            'eliminar tipo beca',

            // Roles y permisos
            'administrar roles',
            'administrar permisos',

            // Admin
            'administrar usuarios',

            // Fotos
            'subir fotos',

            // Reportes
            'descargar reportes',
        ];

        // Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol administrador y asignar todos los permisos
        $rolAdmin = Role::firstOrCreate(['name' => 'administrador']);
        $rolAdmin->syncPermissions($permissions);

        // Asignar rol al primer usuario (id=1)
        $user = User::find(1);
        if ($user) {
            $user->assignRole('administrador');
        }
    }
}

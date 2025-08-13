<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permisos = [
            'ver roles',
            'ver usuarios',
            'crear usuario',
            'editar usuario',
            'eliminar usuario',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        $rolAdmin = Role::firstOrCreate(['name' => 'administrador']);
        $rolAdmin->syncPermissions($permisos);

        $user = User::find(1);
        if ($user) {
            $user->assignRole('administrador');
        }
    }
}

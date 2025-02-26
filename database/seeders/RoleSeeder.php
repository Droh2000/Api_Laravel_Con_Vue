<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    // Aaqui vamos a crear los datos para roles y permisos y llevarlos a la BD
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Estos son los roles con los cuales vamos a trabaajar
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Editor']);

        // Creamos los permisos (Aqui usamos la convecion de: NombrePermiso.NombreEntidad.NombreDeLaAccion)
        Permission::create(['name' => 'editor.post.index']);
        Permission::create(['name' => 'editor.post.create']);
        Permission::create(['name' => 'editor.post.update']);
        Permission::create(['name' => 'editor.post.destroy']);

        Permission::create(['name' => 'editor.category.index']);
        Permission::create(['name' => 'editor.category.create']);
        Permission::create(['name' => 'editor.category.update']);
        Permission::create(['name' => 'editor.category.destroy']);
    }
}

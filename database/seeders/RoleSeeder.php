<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    // Aqui vamos a crear los datos para roles y permisos y llevarlos a la BD
    // ESTO ES SOLO PARA PUEBAS ya mas adelantes crearmos el Dashboard y desde ahi se asignaran
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Estos son los roles con los cuales vamos a trabaajar
        /*$role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Editor']);

        // Creamos los permisos (Aqui usamos la convecion de: NombrePermiso.NombreEntidad.NombreDeLaAccion)
        Permission::create(['name' => 'editor.post.index']);
        Permission::create(['name' => 'editor.post.create']);
        Permission::create(['name' => 'editor.post.update']);
        Permission::create(['name' => 'editor.post.destroy']);

        Permission::create(['name' => 'editor.category.index']);
        Permission::create(['name' => 'editor.category.create']);
        Permission::create(['name' => 'editor.category.update']);
        Permission::create(['name' => 'editor.category.destroy']);*/

        // Comentamos las lineas de arriba para evitar generar generar duplicados

        // Asignar Roles a Permisos
        // El Rol de identificado de 1 seria el de administrador
        Permission::find(1)->assignRole(Role::find(1));
        Permission::find(1)->assignRole(Role::find(2));
        // Despues de esto se ejecuta: php artisan db:seed --class=RoleSeeder

        // Asignar Permisos a Roles
        // Primero buscamos el Rol y despues le asignamos el Permiso elegido
        $role2 = Role::find(2);

        /*Permission::find(1)->assignRole($role2);
        Permission::find(2)->assignRole($role2);
        Permission::find(3)->assignRole($role2);
        Permission::find(4)->assignRole($role2);
        Permission::find(5)->assignRole($role2);
        Permission::find(6)->assignRole($role2);
        Permission::find(7)->assignRole($role2);
        Permission::find(8)->assignRole($role2);*/

        // Proces Inverso 
        // Buscamos los permisos de manera individual 
        $permission1 = Permission::find(1);
        $permission2 = Permission::find(2);
        $permission3 = Permission::find(3);
        $permission4 = Permission::find(4);
        $permission5 = Permission::find(5);
        $permission6 = Permission::find(6);
        $permission7 = Permission::find(7);
        $permission8 = Permission::find(8);

        // Anteriormente teniamos el permiso y le asignabamos el rol y ahora 
        // tenemos el Rol y le asignammos el permiso (Esto lo hacemos para ver defirentes metodos)
        // Como este es un metodo que recibe multiple argumentos le pasamos todos los permisos
        $role2->givePermissionTo(
            $permission1,
            $permission2,
            $permission3,
            $permission4,
            $permission5,
            $permission6,
            $permission7,
            $permission8)
        // Despues de sto ejecutamos el seeder: php artisan db:seed --class=RoleSeeder
        // Al final si vemos las tablas en la base de datos veremos que alfina equivala a lo mismo porque son los mismos datos

    }
}

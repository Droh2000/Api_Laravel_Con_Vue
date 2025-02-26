<?php

namespace App\View\Components\Dashboard\role\permission;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Manage extends Component
{
    // Aqui colocamos el ROL (Que recibira este componente y debemos inicializarlo)
    // public $role;

    public function __construct(public Role $role) // Si ponemos el public aqui nos ahorramos las dos lineas comentadas 
    {
        // $this->role = $role
    }

    public function render(): View|Closure|string
    {
        // Aqui regresamos los permisos 
        // Aqui le agregamos los permisos a la vista para tener los datos y manejarlos internamente
        return view('components.dashboard.role.permission.manage', ['permissionsRole' => $this->role->permissions, 'permissions' => Permission::get()]);
    }

    // Para asignar permisos al rol mediante un formulario
    public function handler(Role $role)
    {
        // Vamos a recibir el permiso
        // Con el metodo de "findOrFail" es que si no encuentra el registro nas da una excepcion de 404 (Asi evitamos hacer validaciones adicionales)
        // En el request le mandamos el Permiso
        $permission = Permission::findOrFail(request('permission'));
        $role->givePermissionTo($permission);
        // Lo mandamos a la pagina anterior 
        return redirect()->back();
    }
}

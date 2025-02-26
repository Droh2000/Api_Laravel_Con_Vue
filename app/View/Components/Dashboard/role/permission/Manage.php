<?php

namespace App\View\Components\Dashboard\role\permission;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Permission\Models\Role;

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
        return view('components.dashboard.role.permission.manage', ['permissionsRole' => $this->role->permissions]);
    }
}

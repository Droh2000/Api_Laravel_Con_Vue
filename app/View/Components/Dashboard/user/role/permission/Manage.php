<?php

namespace App\View\Components\Dashboard\user\role\permission;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Manage extends Component
{
    /*
        Aqui vamos a recibir el usuario de los que tenemos en el Dashboard
        cuando queremos gestionarlos en algunas de las opciones 
    */
    public function __construct(public User $user)
    {
        //
    }

    public function render(): View|Closure|string
    {
        // Aqui le pasamos solo el ROL ya que el usuario no hace falta pasarlo porque ya lo tenemos declarado en el contructor
        // asi que se autosuministra a esta vista (Le mandamos todo el listado de los roles)
        return view('components.dashboard.user.role.permission.manage', ['role' => Role::get()]);
    }
}

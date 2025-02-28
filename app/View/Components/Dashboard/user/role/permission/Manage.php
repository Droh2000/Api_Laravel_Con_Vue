<?php

namespace App\View\Components\Dashboard\user\role\permission;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class Manage extends Component
{
    /*
        Aqui vamos a recibir el usuario de los que tenemos en el Dashboard
        cuando queremos gestionarlos en algunas de las opciones 
    */
    public function __construct(public User $user)
    {
        // Al ver el contenido nos da una coleccion como un array donde tenemos los roles de ese usuario
        // dd($user->roles);
        // Si le colocamos los parentesis nos sale la Realcion para colocar Queries o Where
        // dd($user->roles());
    }

    public function render(): View|Closure|string
    {
        // Aqui le pasamos solo el ROL ya que el usuario no hace falta pasarlo porque ya lo tenemos declarado en el contructor
        // asi que se autosuministra a esta vista (Le mandamos todo el listado de los roles y todo el listado de los permisos)
        return view('components.dashboard.user.role.permission.manage', ['roles' => Role::get(), 'permissions' => Permission::get()]);
    }

    //*****  ROLES
    // Estos metodos los renombramos agregandoles la parte de Role o Permission para no tener conflictos ya que aqui creamos ROLES y permisos
    public function handleRole(User $user)
    {
        Gate::authorize('is-admin');
        // Aqui entre comillas le colocamos 'role' en el manage.blade.php del usuario dentro de la funcion assignRoleToUser()
        // dentro del axios.post
        $role = Role::findOrFail(request('role'));
        $user->assignRole($role);// Le asignamos el rol al usuario que estamos recibiendo

        if (request()->ajax()) {
            //axios, jquery ajax fetch...
            return response()->json($role);
        } else {
            //form
            return redirect()->back();
        }
    }

    function deleteRole(User $user)
    {
        Gate::authorize('is-admin');
        // Aqui le colocamos "Role" porque asi fue como le pusimo en el manage.blade.php dentro de la funcion 
        // "setListenerToDeleteRole" en el "axios.post"
        $role = Role::findOrFail(request('role'));
        $user->removeRole($role);

        if (request()->ajax()) {
            //axios, jquery ajax fetch...
            return response()->json($role);
        } else {
            //form
            return redirect()->back();
        }
    }

    //*****  PERMISSIONS
    // Este es el equema contrario en el cual le podemos asignar permisos al usuario 
    // Para casos muy especificos en el cual no queramos crear ROLES adicionales

    public function handlePermission(User $user)
    {
        Gate::authorize('is-admin');
        $permission = Permission::findOrFail(request('permission'));
        $user->givePermissionTo($permission);

        if (request()->ajax()) {
            //axios, jquery ajax fetch...
            return response()->json($permission);
        } else {
            //form
            return redirect()->back();
        }
    }

    function deletePermission(User $user)
    {
        Gate::authorize('is-admin');
        $permission = Permission::findOrFail(request('permission'));
        $user->revokePermissionTo($permission);

        if (request()->ajax()) {
            //axios, jquery ajax fetch...
            return response()->json($permission);
        } else {
            //form
            return redirect()->back();
        }
    }
}

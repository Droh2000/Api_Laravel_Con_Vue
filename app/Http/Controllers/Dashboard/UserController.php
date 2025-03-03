<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PutRequest;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index()
    {
        // $users = User::paginate(10);
        // Aqui preguntamos si el usuario tiene acceso a la operacion "editor.user.index"
        if (!auth()->user()->hasPermissionTo('editor.user.index')) {
            return abort(403);
        }

        // Tenemos dos tipos de usuarios, uno administracion y otro regular (El administrador siempre tiene que estar por encima)
        // por eso tenemos esta comparacion (El administrador debe de traer todos los usuarios que esten por debajo de el y el Regular no tiene porque ver los usuarios admin)
        // El WHEN es un condicional que si se cumple la condicion pasda ejecuta el codigo de la funcion
        // Aqui preguntamos que si el usuario no es adminstrador entonces solo hay usuarios regulares
        $users = User::when(!auth()->user()->hasRole('Admin'), function ($query, $isAdmin) {
            // Preguntamos si el ROL es regular regresamos los usuarios de tipo regular
            return $query->where('rol', 'regular'); // regular = editor
        })->paginate(10);
        // El problema de solo implementar eso es que aun asi los usuarios regulares pueden ver los administradores desde la URL y editarlos
        // asi que empleamos en app\Providers\AppServiceProvider.php, aplicando usos de los Gates para definir una regla de acceso
        // que por definicion eso es un permiso 


        return view('dashboard/user/index', compact('users'));
    }

    public function create()
    {
        // Nos podemos crear un Gate especifico para esta y evitar tener que crear esta condicion empleando ya todo dentro del Gate
        if (!auth()->user()->hasPermissionTo('editor.user.create')) {// Primero se verifica si el usuario tiene este permiso
            return abort(403);
        }

        $user = new User();
        return view('dashboard.user.create', compact('user'));
    }

    public function store(StoreRequest $request)
    {
        if (!auth()->user()->hasPermissionTo('editor.user.create')) {// Igual se verifica si tiene primero el permiso
            return abort(403);
        }
        /*
            En un inicio por defecto solo creaba usuarios rgulares y no adiminstradores
            entonces vamos a exponer el request para poder especificarle manualmente el tipo del rol del usuario
            asi probamos que las acciones que pueda realizar si estan disponibles
        */
        $data = $request->validated();
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'rol' => 'admin', // le especifcamos para que cuando cree usuario lo haga con el rol de administrador
        ]);
        return to_route('user.index')->with('status', 'User created');
    }

    public function show(User $user)
    {
        return view('dashboard/user/show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        // Politica para que solo el administrador puede realizar (La funcion ya por defecto se le pasa el usuario administrador)
        // el usuario que le pasamos es el que esta ejecutando la accion
        Gate::authorize('update-view-user-admin', [$user, 'editor.user.update']);// Le pasamos como segundo parametro el permiso y nos ahorramos el condicional
        return view('dashboard.user.edit', compact('user'));
    }

    public function update(PutRequest $request, User $user)
    {
        Gate::authorize('update-view-user-admin', [$user, 'editor.user.update']);// Le pasamos como segundo parametro el permiso y nos ahorramos el condicional
        $user->update($request->validated());
        return to_route('user.index')->with('status', 'User updated');
    }

    public function destroy(User $user)
    {
        Gate::authorize('update-view-user-admin', [$user, 'editor.user.destroy']);// Le pasamos como segundo parametro el permiso y nos ahorramos el condicional
        $user->delete();
        return to_route('user.index')->with('status', 'User delete');
    }
}
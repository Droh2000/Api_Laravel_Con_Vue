<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Aqui definimos los Gate
        // Con este metodo lo creamos y le pasamos entre comillas el identificador, siempre recibimos el usuario
        // despues le manamos la publicacion "Post"
        /*Gate::define('update-post', function($user, $post){
            // Mensaje de debug para ver que es lo que esta pasando
            //dd($user); -> Vemos la instancia del usuario
            //dd($post); -> Vemos la informacion del Post

            // Evaluamos una sola condicion que es lo que nos interesa y de ahi regresara un booleano 
            // Aqui podemos colocar cualquier cantidad de reglas que queramos
            return $user->id == $post->user_id;
        });*/

        // Gate para que el usuario Regular no pueda acceder a los Admins desde la URL
        // Le definimos entre '' el nombre que queramo, en la funcion recibe el usuario autenticado y ademas recibira el otro usuario
        // para que un usuario regular pueda efitar otos usuarios regulares 
        Gate::define('update-view-user-admin', function ($user, $userParams, $permissionName) {
            // Si el usuario es administrador o no es admin (El usuario regular no podra ejecutar la accion, si no es Admin vamos a derificar que el que quiere verificar) 
            // y ademas el usuario tiene permisos que se le pase (Editar, eliminar, actualizar)
            return ($user->hasRole('Admin') || !$userParams->hasRole('Admin')) && auth()->user()->hasPermissionTo($permissionName);
        });

        // Todos estos esquemas de sguridad depende de lo que se nos solicite no se tiene porque implementar todos
        // aqui preguntamos directamente por el ROL, asi el Admministrador ya tiene estos accesos
        // Verificamos si es administrador
        Gate::define('is-admin', function ($user) {
            return $user->hasRole('Admin');
        });
    }
}

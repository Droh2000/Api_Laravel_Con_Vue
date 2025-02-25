<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        Gate::define('update-post', function($user, $post){
            // Mensaje de debug para ver que es lo que esta pasando
            //dd($user); -> Vemos la instancia del usuario
            //dd($post); -> Vemos la informacion del Post

            // Evaluamos una sola condicion que es lo que nos interesa y de ahi regresara un booleano 
            // Aqui podemos colocar cualquier cantidad de reglas que queramos
            return $user->id == $post->user_id;
        });
    }
}

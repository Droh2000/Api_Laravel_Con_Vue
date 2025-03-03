<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    // Fijemonos que aqui tenemos los metodos como en los controladores, cabe aclarar que al ejecutar el comando
    // tubimos que especificar el modelo con --model:Post

    // Este metodo no lo vamos a usar asi que lo comentamos
    /**
     * Determine whether the user can view any models.
     */
    /*public function viewAny(User $user): bool
    {
        //
    }*/

    // Implementacion minima del Before
    // En nuestro caso no es muy ulti porque tenemos segementado el proyecto entre modulos, es decir un modulo de Web y Dashboard
    // por lo tanto si tenemos muchos CRUDS siempre se tiene que cumplir las mismas condiciones que sen este caso el usario se de 
    // tipo administrados pero puede que tengamos modulos mas flexibles o en la evaluacion de Reglas y puede que algunas entidad si requeiran
    // tener ciertos permisos (En esta app que algunos si puedan ser Admins y otros que no)
    /*function before(User $user) : bool | null{
        //if($user->isAdmin()){
        if($user->accessDashboard()){ // Usamos el metodo creaddo en: app/Modeks/User
            // Si es nulo va a seguir con la ejecucion como lo hace normalmente
            // si fuera True estaria detiendo la ejecucion y no le damos acceso
            return null;
        }
        return false; // Regresamos esto si la condicion no se cumple (Ejecutamos otro Gate para que lo evalue)
    }*/
    // El metodo de arriba se comento porque hay una dualidad con el de middleware en UserAccessDashboardMiddleware.php
    

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // Queremos que la vista la vean completamente para todos los usuarios
        // Aqui podemos colocar una regla en base a algo
        return true;
    }

    // Esta funcion la podemos comentar porque ya en esta aplicacion mediante los midlewares estamos indicando que el usuario debe 
    // estar autenticado (En el archivo web.php) y tambien creamos los midlewares con "UserAccessDashboardMiddleware" donde indicamos que es isAdmin
    // Por eso no nos sirve este metodo, por tanto podemos retornar cualquier cosa 
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Esto equivale a colcar TRUE (Si da False significa que no estamos autenticados)
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): Respose
    {
        // Definimos la misma regla que definimos en los Gates donde solo el usuario que creo el POST puede actualizar
        // En este caso vamos a regresar una respuesta  (Del Response tenemos los metodos para permitir el acceso o Denegar)
        // Aqui si la condicion se cumple permitimos el acceso
        return $user->id == $post->user_id ? Response::allow() : Response::deny('No eres el usuario deno del Post');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Misma regla que definimos en los Gates
        return $user->id == $post->user_id;

    }

    /*
    
    Estos metodos no los vamos a usar asi que los comentamos

        Determine whether the user can restore the model.
     
    public function restore(User $user, Post $post): bool
    {
        
    }
        Determine whether the user can permanently delete the model.
     
    public function forceDelete(User $user, Post $post): bool
    {
        
    }
    */
}

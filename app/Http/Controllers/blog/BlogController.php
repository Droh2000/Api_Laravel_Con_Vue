<?php

namespace App\Http\Controllers\blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    function index(){
        // Obtenemos los Posts
        $posts = Post::paginate(2);
        return view('blog.index', compact('posts'));
    }

    // Aqui como argumentos lo mejor seria regresar solo el ID para que sea mas eficiente
    // por tanto el uso del POST aqui no tiene sentido, la razon del argumento en la funcion es que 
    // estamos inyectando para traducir el ID (que es lo que empleamos en la URL) y se traduce automaticamente a POST
    // para buscar por ese ID (De manera automatica ejecuta "Post::find(1)")
    // Como estamos por la cache no tiene sentido ejecutar operaciones a la BD asi que cambiemos este POST por el ID o el Slug
    // En el caso que no este definido en el cache si empleamos el POST pero en este caso solo buscamos una sola vez con el post
    // Para implementar este cambio la ruta en "web.php"
    //  function show(Post $post){
    function show(int $id){

        // Implementacion del Cache
        // Preguntamos por la Key si existe (En caso que no tomara los datos del almacenamiento de la BD)
        // La key debe ser unica porlo que podria ser el SLUG o un ID, entre las comillas le colocamos un prefijo
        // (No pusimos el SLUG porque se puede repetir)
        /*if(Cache::has('post_show_'.$post->id)){
            // Regresamos lo que tengamos en la cache (Le pasamos la misma key)
            return Cache::get('post_show_'.$post->id);
        }else{
            // Regresamos la Vista (Es el contenido HTML) y lo almacenamos en variable y el metodo render para que lo renderize
            $cacheView = view('blog.show', ['post'=>$post])->render();
            // Debug para ver el contenido HTML
            // Aqui vemos que nos regresa todo el contenido HTML por que usamos el metodo "VIEW()" que renderiza el contenido de la url en "blog.show"
            // y ademas tenemos la plantilla maestra en "show.blade.php" que seria la de @extends
            // dd($cacheView);
            // Almacenamos el contenido en la cache (Especificandole la Key y el contenido)
            Cache::put('post_show_'.$post->id, $cacheView);
            return $cacheView; // Regresamos el contenido para mostrarselo al usuaario
        }*/

        // La desventaja de esto es que si actualizamos la publicacion del cache estos cambios no afectaran a la cache
        // Esto se tendria que implementar desde la opcion de actualizar el registro con el SLUG y con la Key, se elimina la cache y se vuelve a guardar el contenido
        // Esto lo implementamos en le PostController.php

        // Tambien tenemos el metodo de ayuda que es en lugar de usar: Cache:: se usa cache()-> (Toda la demas logica se implementa igual)

        // Implementacion usando el metodo del rememberForever (La logica interna de la funcion solo se ejecuta cuando no existen los datos en la cache)
        return Cache::rememberForever('post_show_'.$id, function () use($id){

            // Esto se va a hacer solo cuando no exista en cache sino retorna por defecto ya la almacenado
            // Aqui tambien podemos implementar varias cosas de seguridad como el 404
            // En este caso con el metodo de with() que es de Eloquent indicamos que nos extraiga la relacion de la FK (Es "category" porque asi se llama el metodo en el archivo Post.php)
            $post = Post::with('category')->find($id);

            // Aqui ejecutamos la operacion a la base de datos
            // Por defecto la variable $post abajo nos daba error para estos casos en el que queremos emplear argumentos de funciones dentro de este Callback
            // ya que por defecto no lo toma entonces tenemos que colocar arriba despues de "function ()" el 'use()' y dentro le colocamos los argumentos
            // Asi mapeamos el argumento
            return view('blog.show', ['post'=>$post])->render();
        });

        //return view('blog.show', ['post'=>$post]);
    }
}

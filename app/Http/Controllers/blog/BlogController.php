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
    function show(Post $post){

        // Implementacion del Cache
        // Preguntamos por la Key si existe (En caso que no tomara los datos del almacenamiento de la BD)
        // La key debe ser unica porlo que podria ser el SLUG o un ID, entre las comillas le colocamos un prefijo
        // (No pusimos el SLUG porque se puede repetir)
        if(Cache::has('post_show_'.$post->id)){
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
        }

        // La desventaja de esto es que si actualizamos la publicacion del cache estos cambios no afectaran a la cache
        // Esto se tendria que implementar desde la opcion de actualizar el registro con el SLUG y con la Key, se elimina la cache y se vuelve a guardar el contenido
        // Esto lo implementamos en le PostController.php

        //return view('blog.show', ['post'=>$post]);
    }
}

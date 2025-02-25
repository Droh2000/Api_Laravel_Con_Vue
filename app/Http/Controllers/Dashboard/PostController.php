<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller; 
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\PutRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(2); // Obtenemos todos los posts
    
        return view('dashboard/post/index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::pluck('id', 'title');
        
        $post = new Post();

        return view('dashboard/post/create', compact('categories','post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //Post::create($request->validated());

        // Primero creamos un nuevo Post al cual le pasamos la Data
        $post = new Post($request->validated());
        // Le asignamos el usuario (Con auth obtenemos una instancia del modelo del usuario)
        // asi podemos acceder a sus metodos y propiedades (SEria la funcion que creamo), asi guardamos
        // el usuario pasandole el Post
        auth()->user()->posts()->save($post);

        // Para ver si funciona esto debemos de crear un nuevo Post
        // Ademas con: auth()->user()->posts() si lo ponemos un la funcion "dd()" nos da la relacion "HasMany"
        // con esto podemos realizar cualquier tipo de operacion CRUD y con:
        // auth()->user()->posts, obtenemos los datos directamente

        return to_route('post.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard/post/show',['post'=> $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

        // Uso del Gate (Entre comillas le pasamos el nombre queremos emplear) y le pasamos el parametro que definimos
        // el del usuario no hace falta pasarlo
        // Si no esta definido el 'update-post' nos retorna Falso y nos bloquea
        if(!Gate::allows('update-post', $post)){
            // Aqui bloqueamos el acceso (Aqui sol regresamos un 403)
            return abort(403);
        }

        $categories = Category::pluck('id', 'title');
        
        return view('dashboard/post/edit', compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutRequest $request, Post $post)
    {
        // Asi usamos las politicas solo entre las comillas le especificamos el nombre del metodo que queremos ejecutar del archivo
        // Primero veririfcamos si el usuario tiene el permiso de poder actualizar el POST

        // Con el metodo Allows() tenemos limitantes ya que solo con True o False lo niega y como aqui estamos regresando una respuesta no un Bool de todos modos lo niega
        // En este caso de las respuestas podemos usar el metodo "inspect()"
        //if(!Gate::allows('update', $post)){
        // Con este metodo del "inspect" preguntamos de manera manual con el argumento "->allowed()" y asi podemos acceder al mensaje con "->message()"
        if(!Gate::inspect('update', $post)->allowed()){
            // Aqui bloqueamos el acceso (Aqui sol regresamos un 403)
            // Despues Implementamos el dar una respuesta HTTP pero si no modificamos aqui seguremos viendo solo el mensaje 403
            return abort(403, Gate::inspect('update', $post)->message());
        }

        $data = $request->validated();

        if(isset($data['image'])){

            $data['image'] = $filename = time().'.'.$data['image']->extension();

            $request->image->move(public_path('uploads/posts'), $filename);
        }

        // Actualizacion de la Cache
        // Primero eliminamos lo que ya teniamos almacenado, asi cuando se vuelve acceder a la ruta como ya no hay cache se volvera a guardar en el cache 
        Cache::forget('post_show_'.$post->id);

        $post -> update($data)
        return to_route('post.index')->with('status', 'Post updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('post.index');
    }
}

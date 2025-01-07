<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PutRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{

    public function all()
    {
        return response()->json(Post::get());
    }

    public function index()
    {
        // Aqui se le modifico para obtener todas las categorias por la relacion del POST
        // Aqui la obtiene para todos los POSTs y solo le pasamos el nombre de la relacion 'category'
        return response()->json(Post::with('category')->paginate(10));
    }

    public function store(StoreRequest $request)
    {
        return response()->json(Post::create($request->validated()));
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function slug(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return response()->json($post);
    }

    public function update(PutRequest $request, Post $post)
    {
        $post->update($request->validated());
        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json('ok');
    }

    function upload(Request $request, Post $post) {

        // Validaciones para que solo acepte los archivos permitidos
        $request->validate([
            'image' => 'required|mimes:jpg, jpeg, png, gif|max:10240'
        ]);

        // Configuracion para eliminar le pasamos la ruta
        // Asi lo que hacemos es borrar la imagen si ya existe en un post y no haiga duplicados o mas
        Storage::disk('public_upload')->delete('image/'.$post->image);

        // Primero generamos el nombre de la imagen
        // El time es para darle un nombre unico de archivo
        // e; nombre ['image'] es el que definimos en el Modelo de Post en la variable Fillable
        $data['image'] = $filename = time() . '.' . $request->image->extension();

        // Metodo de public path nos regresa la carpeta public que es la unica que se puede acceder mediante
        // el navegador y le indicamos que almacenaremos en la carpeta 'image'
        $request->image->move(public_path('image'), $filename);

        $post->update($data);

        return response()->json($post);
    }
}

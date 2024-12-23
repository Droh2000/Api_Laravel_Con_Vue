<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PutRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;

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
        $category = Post::where('slug', $slug)->firstOrFail();
        return response()->json($category);
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
}

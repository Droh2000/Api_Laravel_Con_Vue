<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    // Para Eliminar el token
    // Con el uuario (Autenticado) 
    // Esto lo colcamos aqui para ver como se muestra la informacion en la consola en parte de Network
    //dd($request->user()->currentAccessToken());

    return $request->user();
})->middleware('auth:sanctum'); // Esto es para proteger las rutas y solo sera posible el acceso despues de iniciar session
// La proteccion de las rutas ya depende de nosotros que queremos proteger

// Como desmostracion vamos a proteger algunas Rutas
// Si no se inicio sesion obtendremos el error 401
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('category', CategoryController::class)->except(['create', 'edit']);
    Route::resource('post', PostController::class)->except(['create', 'edit']);
});

Route::get('category/all', [CategoryController::class, 'all']); // Esto no da error 404 si no queremos que nos salga hay que poner estas lineas de codigo arriba del middleware el problema es que el usuario sin iniciar sesion tendra acceso
Route::get('category/slug/{category:slug}', [CategoryController::class, 'slug']);

Route::get('post/all', [PostController::class, 'all']);
Route::get('post/slug/{slug}', [PostController::class, 'slug']);

// Definimos la ruta para subir archivos
Route::post('post/upload/{post}',[PostController::class, 'upload']);

// Ruta de la autenticacion
// La ruta tambien la registramos a nivel de Web para hacer otro tipo de pruebas
// Route::post('user/login',[LoginController::class, 'authenticate']);

// Autenticacion con Tokens
Route::post('user/login',[UserController::class, 'login']);

// Cerrar la Sesion
// Usualmente se usa la peticion de POST porque estamos en principio cambiando el esquema de datos
// que en este caso es para destruir la sesion
Route::post('user/logout',[UserController::class, 'logout'])->middleware('auth:sanctum'); // Esto se podra hacer si esta la session iniciada

// Para verificar el token si esta activo o NO
// El parametro se va a mandar mediante GET 
Route::post('user/token-check}',[UserController::class, 'checkToken']);
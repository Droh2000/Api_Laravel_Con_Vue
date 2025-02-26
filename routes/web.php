<?php

use App\Http\Controllers\blog\BlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\UserAccessDashboardMiddleware;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
    Habia un Error que cuando estabamos en una pagina que hacia la peticion al servidor como
    la de Crear o Editar, al momento de recargar esa pagina nos salia 404 NOT FOUND

    Eso pasa porque hace la peticion a Laravel y en este archivo verifica la ruta
    pero no existe con el parametro de Editar o Crear por eso lanza los 404

    En este caso los creamos con argumentos porque el de Editar esta para cada uno de los registros
    que existan y es opcional con "?", lo nombres son como se nos de la gana
        {n1?} -> Para el apartado de la pagina "Save" o "Category"
        {n2?} -> Seria el argumento que puede recibirse como el ID del registro en la pagina de Editar
            Podemos poner tanto como requiramos si tenemos una URL mas compleja
            Asi no tenemos que crear un ROUTE para la pagina de Save y otra para Editar y asi
            Esta misma nos sirve para todas las demas paginas
            Realmente le damos estos nombres Randoms porque no vamos a implementar una logica con ellos
*/
Route::get('/{n1?}/{n2?}/{n3?}', function () {
    return view('vue');
});

Route::post('/user/login',[LoginController::class, 'authenticate']);

// Estas lineas se agregaron por la implementacion del cache sin el uso del POST 
// pero en si esta logica es del proyecto original de Laravel solo que el tipo del curso no especifico en ningun momoento cual uso
// Asi que se opto por seguir usando donde se creo la API
// Rutas para el Blog que es donde implementamos los componentes
Route::group(['prefix' => 'blog'], function() {
    Route::get('', [BlogController::class, 'index'])->name('blog.index');
    //Route::get('detail/{post}', [BlogController::class, 'show'])->name('blog.show');

    // Uso del ID en lugar del POST
    Route::get('detail/{id}', [BlogController::class, 'show'])->name('blog.show');
});

// Ruta de ejemplo para la implementacion del Cache de las rutas
Route::get('/', function () {
    // app es una funcion donde podemos obtener datos del framework
    return ['Laravel' => app()->version()];
});
// Despues ejecutamos: php artisan route:cache -> Con esto registra el cache de las rutas
// Una vez ejecutada si creamos otra ruta o modificamos la ruta estos cambios no se veran reflejados y
// es por eso que se recomienda ejecutar este comando en produccion
// Para volver a ver los cambio reflejados se ejecuta: php artisan route:clear

// Creamos la Ruta para la interfaz que maneja los Roles y Permisos
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', UserAccessDashboardMiddleware::class]], function () {
    Route::resources([
        'post' => App\Http\Controllers\Dashboard\PostController::class,
        'category' => App\Http\Controllers\Dashboard\CategoryController::class,
        'role' => App\Http\Controllers\Dashboard\RoleController::class,
        'permission' => App\Http\Controllers\Dashboard\PermissionController::class,
    ]);

    Route::get('', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');
});

require __DIR__ . '/auth.php';
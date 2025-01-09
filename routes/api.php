<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('category/all', [CategoryController::class, 'all']);
Route::get('category/slug/{category:slug}', [CategoryController::class, 'slug']);
Route::resource('category', CategoryController::class)->except(['create', 'edit']);

Route::get('post/all', [PostController::class, 'all']);
Route::get('post/slug/{slug}', [PostController::class, 'slug']);
Route::resource('post', PostController::class)->except(['create', 'edit']);

// Definimos la ruta para subir archivos
Route::post('post/upload/{post}',[PostController::class, 'upload']);

// Ruta de la autenticacion
Route::post('user/login',[LoginController::class, 'login']);


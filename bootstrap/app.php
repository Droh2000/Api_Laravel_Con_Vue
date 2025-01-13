<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // SPA Autenticacion (Esta la podemos dejar activa despues de implementar con la de Tokens)
        // HAy que recordar que si hacemos pruebas y algo que no deberia de funcionar funciona es porque tenemos esta sesion activa 
        // de la SPA entonces hay que desactivarla
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(NotFoundHttpException $e, $request){
            // dd($request->wantsJson());
            if($request->expectsJson()){
                return response()->json('Not found',404);
            }
        });
    })->create();

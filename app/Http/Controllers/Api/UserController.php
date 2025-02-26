<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

// controlador empleado para Sanctum con el manejo de tokens
// Aqui no vamos a crear la sesion sino que vamos a crear el Token
class UserController extends Controller
{
    function login(Request $request)
    {

        $validator = validator()->make(
            $request->all(),
            [
                'email' => 'required',
                'email',
                'password' => 'required'
            ]
        );

        // Si las credenciales son incorrectas regresamos un error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Creamos las credenciales
        $credentials = $validator->valid();

        // Aqui usamos la funcion de auth() y no el metodo de Facades
        if (auth()->attempt($credentials)) {
            // Aqui creamos el token
            // Con "user()" obtenemos el usuario autenticado, a 'createToken()' le psamos una semilla
            // plainTextToken seria lo que vamos a compartir, esta es una propieadad para compartir el token de manera segura
            //  asi podemos acceder a los modulos protegidods
            $token = auth()->user()->createToken('myapptoken')->plainTextToken;
            
            // Le establecemos en la sesion el token creado (Almacenamos el token en la sesion de Laravel)
            session()->put('token', $token);
            // Esto se lo tenemos que pasar a VUE (Comunicando al servidor con el cliente)
            // para eso tenemos el intermediario que vendira siendo nuestra pagina HTML que en este caso es 'vue.blade.php'

            // Regresamos en forma de JSON los datos
            // Aqui vamos a regresar mas informacion del inicio de sesion y no solo el token
            return response()->json(
                [
                    'isLoggedIn' => true, // Aqui ya estamos autenticados
                    'token' => $token,
                    'user' => auth()->user(),
                ]
            );
        }

        return response()->json('The username and/or password do not match', 422);

    }

    // Cerrar la session
    function logout(Request $request)
    {
        // Eliminacion del Token
        // Primero verificamos si es distinto de NULL y asi evitamos que los usuarios puedan ver errores
        if ($request->user()) {

            // Tenemos varias formas de hacer lo mismo por eso unas lineas estan comentadas
            // auth()->user();
            // $request->user()->currentAccessToken()->delete();
            $request->user()->tokens()->delete(); // Con esta funcion eliminamos todos los tokens
        }

        session()->flush(); // Destruimos la session
        // Aqui tambien podriamos colocar la parte de destruir las cookies 
        return response()->json('ok');
        // Despues de esta implementacion creamos la ruta en "api.php"
    }

    function checkToken(){
        // Colocamos en Try/Catch ya que la conversion de Hash puede regresarnos una Excepcion
        try{
            // Aqui tenemos los tokens y por tatnto hacemos la sepearion de ID con el contenido del Token
            // y los dividimos por el caracter del "|" con el "request" le mandamos el parametro que nos va a enviar el cliente
            // Aqui estamos desestructurarndo con la asignacion del arreglo
            [$id, $token] = explode('|', request('token'));

            // Obtenemos el token convertido a Hash que es como viene en la BD
            $tokenHash = hash('sha256', $token);

            // Modelo del Token con el que podemos verificar si el token coincide 
            $tokenModel = PersonalAccessToken::where('token', $tokenHash)->first();

            if($tokenModel){
                //dd($tokenModel -> tokenable); -> Aqui vemos el usuario dueno de ese Token

                // Aqui estamos haciendo login
                // dd(Auth::login($tokenModel -> tokenable)) -> Vimos que esto nos regresa NULL osea podemos omitirlo 
                // Tokenable tiene que ser de tipo Autenticable, esto podemos omitirlo como se dice arriba pero solo cuando sea otra cosa que no sea el usuario
                // eso de autneticable es una caracteristica por el modelo de usuario "User"
                // Ademas por la concion del IF podemos dar por hecho que si nos da TRUe entonces ya no hubo ningun problema
                Auth::login($tokenModel -> tokenable);
                
                return response()->json(
                    [
                        'isLoggedIn' => true, // Aqui ya estamos autenticados
                        'token' => $token,
                        'user' => auth()->user(),
                    ]
                );
            }
        }catch( Exception $e ){}

        // Retornamos que hubo problemas (Esto se pone afuera para que cualquier problema que ocurra automaticamente retorne este mensaje)
        return response()->json('Invalid user', 422);
    }

// Despues de la implementacion de este controlador implementamos la Ruta en API.php 
}
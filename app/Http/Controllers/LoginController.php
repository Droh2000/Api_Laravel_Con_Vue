<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Con esta funcion verificamos si esta entrando o no al ejecutar el Enpoint 'api/user/login
    //dd();
    function authenticate(Request $request){
        // Aqui obtenemos las credenciales y aqui local aplicamos las validaciones
        // En un inicio al poner credenciales correctas obtenemos un error 500, el problema es que 
        // no podemos aplicar este tipo de esquemas ya que esto solo sirve cuando estamos en el mudlo WEB
        // y no es correcta para la Parte del API entonces hay que buscar otros esquemas para aplicar validacion
        // Ademas que tenemos el metodo Validator para validar la Data ya que tenemos mas control y no hace esas
        // redirecciones automaticas que son excelentes para el modulo WEB pero no para el API y es que no es bueno
        // hacer redirecciones porque NO HAY UNA PAGINA ANTERIOR 

        // Primero Activamos las Validaciones (Tenemos el Fadcade 'Validator::' o la funcion de ayuda 'validator()')
        // En este punto tenemos la Respuesta y la Data en caso que se pueda acceder a la Data
        $validator = validator()->make($request->all(),
            [
                'email' => 'required', 'email',
                'password' => 'required'
            ]
        );

        // Como segunda cosa regresamos un error en caso de tener problemas
        // fails() -> Nos regresa True en caso de tener errores
        if($validator->fails()){
            //      return $validator->errors();// Aqui no estamos regresando un JSON
            // Otra forma de hacerlo es asi especificando la funcion JSON
            // Lo que pasa es que como la aplicacion se esta consumiendo en un Content Type de tipo Aplication JSON
            // la linea de codigo de arriva automaticamente laravel la convierte a JSON
            
            // Se utilizo esta para especificarle el codigo de cuando error al ingresar mal las credenciales
            return response()->json($validator->errors(), 422);
        }

        // Aqui las redirecciones no tienen mucho sentido, asi que lo siguiente es obtene la Data validada
        // ya que si estamos en este punto significa que el usuario paso la validacion

        // En este caso sera para el correo y la contrasena
        /*$credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);*/

        // 'valid()' nos retorna la data que es valida
        $credentials = $validator->valid();

        // Ahora intentamos autenticarse (Esto nos regresa True si se autentico)
        if(Auth::attempt($credentials)){
            // Regeneramos la sesion para tener acceso a esos datos
            // Aqui vemos que el esquema es un poco Hibrido ya que aunque no se emplea la session directamente
            // desde terminos funcionales si la emplea, aunque realmente se este cuardando en una Cookie los datos de acceso
            $request->session()->regenerate();
            // Como ya estamos autenticados no tiene sentido hacer redirecciones entonces podemos emplear un mensaje 
            // donde se diga que el usuario esta correctamente autenticado
            // El bloqueo inicial era que no se tiene acceso a la sesion y no tenemos acceso al token y es por eso
            // que en postman falla
            return response()->json('Successful authentication');
        }

        // Caso que regrese false entonces tenemos problemas
        // Nos regresamos a la pagina anterior y cachamos los errores
        /*return back()->withErrors(([
            'email' => 'El usuario o contrasena no son correctos'
        ]))->onlyInput('email');// Le indicamos que solo retorne el email
        */

        // El ultimo paso es cuando no conciden las credenciales
        // Aqui le especificamos el codigo del Status para asi poder capturar la excepcion desde el componente de VUE
        // y asi mostrar los errores de validacion
        return response()->json('The username and/or password do not match',422);
        // Esto seguira sin funcionar en Postman por eso vamos a probarlo de otra manera
    }
}
// Despues le definimos una ruta en "api.php"
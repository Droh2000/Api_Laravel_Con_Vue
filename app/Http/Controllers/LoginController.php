<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Aqui obtenemos las credenciales y aqui local aplicamos las validaciones
    function authenticate(Request $request){
        // En este caso sera para el correo y la contrasena
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }

    // Ahora intentamos autenticarse (Esto nos regresa True si se autentico)
    if(Auth::attempt($credentials)){
        // Regeneramos la sesion para tener acceso a esos datos
        $request->session()->regenerate();
        return;
    }

    // Caso que regrese false entonces tenemos problemas
    // Nos regresamos a la pagina anterior y cachamos los errores
    return back()->withErrors(([
        'email' => 'El usuario o contrasena no son correctos'
    ]))->onlyInput('email');// Le indicamos que solo retorne el email
}
// Despues le definimos una ruta en "api.php"
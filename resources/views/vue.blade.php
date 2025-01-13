<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Cargamos los recursos de Vue  Se puede poner al utimo para asi poder usar el objeto que creamos con "window"-->
    @vite(['resources/js/vue/main.js'])
</head>
<body>
    <!-- 
        Para compartirle el TOKEN que almacenamos en la Sesion se lo compartimos por JavaScript
        Esto lo podemos colocar en cualquier parte 
        
        Aqui preguntamos si estamos autenticados
    -->
    @if(Auth::check())
        <script>
            // Para presentar un Objeto y que no se convierta a un HTML le ponemos los !!
            // Para compartirsela a VUE una forma es estableciendole el objeto windows donde nos creamos una variable personalizada
            // por nosotros y la igualamos a este objeto
            window.Laravel = {!!
                // Le pasamos los parametros que queremos compartir
                json_encode([
                    'isLoggedIn' => true,// Aqui estamos seguro que esta autenticado por la comprobacion 'check()' que paso la condicional
                    'user' => Auth::user(),    // Le pasamos todo el usuario
                    'token' => session('token'),// Aqui le pasamos el token y como ya lo tenemos almacenado en la sesion por la configuracion en "UserController.php"
                ])
            !!}
        </script>
    @else
        <script>
            window.Laravel = {!!
                json_encode([
                    'isLoggedIn' => false
                ])
            !!}
        </script>
    @endif

    <!-- Aqui es donde se va a renderizar nuestra aplicacion en VUE 
        Despues de instalarle el tailwindcss le agregamos el cotainr y para centrar la app
    -->
    <div class="container mx-auto">
        <div id="app"></div>
    </div>
</body>
</html>
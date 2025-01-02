<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Cargamos los recursos de Vue -->
    @vite(['resources/js/vue/main.js'])

</head>
<body>
    <!-- Aqui es donde se va a renderizar nuestra aplicacion en VUE 
        Despues de instalarle el tailwindcss le agregamos el cotainr y para centrar la app
    -->
    <div class="container mx-auto">
        <div id="app"></div>
    </div>
</body>
</html>
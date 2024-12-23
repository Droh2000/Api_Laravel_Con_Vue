<!DOCTYPE html>
<!-- 
        Esta es a vista maestra que es usada como componente en el dashboard
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!--        Page Content 
                
                Aqui es donde vamos a estar agregando para renderizar nuestra aplicacion
            -->
            <main>
                <!-- Estilo ya predefinido para que los componentes no se vean muy estirados en la pantalla y mx-auto para centrarlo-->
                <div class="container mx-auto">

                    @if(session('status'))
                        <div class="card card-success my-3">
                            {{session('status')}}
                        </div>
                    @endif

                    <div class="card card-white mt-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
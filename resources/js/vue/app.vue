<template lang="">
    <div>
        <!-- Este es un componente -->
        <!-- <list/> -->

        <nav class='bg-white border-b border-gray'>
            <header class='max-w-7xl px-4 sm:px-6 lg:px-8'>
                <div class='flex justify-between'>
                    <div class='flex items-center'>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="40"
                            height="35"
                            viewBox="0 0 262 227"
                            >
                            <g id="Vue.js_logo_strokes" fill="none" fill-rule="evenodd">
                                <g id="Path-2">
                                <polyline
                                    class="outer"
                                    stroke="#4B8"
                                    stroke-width="46"
                                    points="12.19 -24.031 131 181 250.351 -26.016"
                                />
                                </g>
                                <g id="Path-3" transform="translate(52)">
                                <polyline
                                    class="inner"
                                    stroke="#354"
                                    stroke-width="42"
                                    points="15.797 -14.056 79 94 142.83 -17.863"
                                />
                                </g>
                            </g>
                            </svg>
                    </div>
                    <div class='max-w-7xl mx-auto py-4 px-4 sm:px-6'>
                        <!--
                            Enlaces de navegacion que solo deben aparecer si estamos autenticados
                            esa condicion se la agregamos al final en "v-if" evaluando la propiedad
                            que nos indica si estamos o no autenticados
                        -->
                        <router-link :to="{'name': 'login'}" class='inline-flex uppercase border-b-2 text-sm-leading-5 mx-3 px-4 py-1 text-gray-600 text-center font-bold hover:text-gray-900 hover:border-gray-700 hover:-translate-y-1 durarion-150 transition-all' v-if="!$root.isLoggedIn">
                            Login
                        </router-link>
                    
                        <router-link :to="{'name': 'list'}" class='inline-flex uppercase border-b-2 text-sm-leading-5 mx-3 px-4 py-1 text-gray-600 text-center font-bold hover:text-gray-900 hover:border-gray-700 hover:-translate-y-1 durarion-150 transition-all' v-if="$root.isLoggedIn">
                            List
                        </router-link>
                        <!-- 
                            Este es el boton para cerrar session y solo lo mostramos si esta iniciada la sesion
                        -->
                        <o-button
                            v-if="$root.isLoggedIn"
                            variant="danger"
                            @click="logout"
                        >
                            Logout
                        </o-button>
                    </div>    
                    <!-- Esto es para mostrar el icono de un Avatar 
                        Solo lo vamos a mostrar si el usuario esta autenticado
                        De las mismas propieades obtenemos el nombre del usuario
                    -->
                    <div class='flex flex-col items-center py-2' v-if='$root.isLoggedIn'>   
                        <div class='rounded-full w-9 h-9 bg-blue-300 text-center p-1 font-bold'>
                            <!-- Del nombre solo obtenemos dde los dos primeros caraccteres 
                                y abajo mostramos el nombre completo
                             -->
                            {{ $root.user.name.substr(0,2).toUpperCase() }}
                        </div>
                        <p>{{ $root.user.name }}</p>
                    </div>
                </div>
            </header>

        </nav>

        <!-- Esto es para cerrar la session 
         <o-button variant="danger" @click='logout'>
            Close Sesion
         </o-button>
        -->
        <!-- 
            Despues de emplear las rutas ahora el componente no se debe poner directamente
            sino que se tiene que emplear la navegacion
            Esta es la seccion donde es posible la navegacion donde en base al URL toma el componente
            que tiene que cargar de manera automatica
                (Esto es el esquema de navegacion simple)
        -->
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from 'axios';

    // Despues de implementar las rutas ya no hace falta esto
    /*
    import List from "./components/ListComponent.vue" 

    // Le indicamos que vamos a usar este componente
    export default{
        components: {
            List
        }
    }*/

    export default {
        // Despues de implementado correctamente el LoginController
        // Primero activemos la conexion de tipo CSRF y asi probar el modulo del login
        // Asi que tomamos cualquier componente "En este caso elegimo el de "App.vue" para hacer la prueba sin dar click a botones"
        mounted(){
            // Esta ruta ya viene por defecto
            /*axios.get('sanctum/csrf-cookie').then(response => {
                // Aqui podemos hacer la peticion al metodo del login
                axios.post('/api/user/login',{
                    // Aqui definimos los datos que le estariamos pasando por postman
                    'email': 'droh@droh.com',
                    'password': 'qwerty'
                }
                ).then(response => {
                    // Imprimos el mensaje que nos da el servidor
                    console.log(response.data);
                }).catch(error => {
                    console.log(error);
                });
            });*/
            // Despues de probar esta linea de codigo entonces ya estamos autenticados, por lo tanto podemos
            // hacer la siguiente peticion que en el archivo de "api.php" estsa protegido con "auth:sanctum"
            /*axios.get('/api/user').then(response => {
                console.log(response.data);
            });*/
            // Asi a las demas rutas que que queramos proteger solo les agregamos al final en "->middleware('auth:sanctum');"
            // en la rutas de "api.php"

            // Este codigo se implemento en el componente de LoginComponent.vue

            // Autenticacion con Tokens (ESto es una prueba para saber lo que tenemos que hacer)
            // El Token viaja en los Headers
            // Con esto podemos ver que el token lo podemos almacenar localment en el cliente como en la cookie o una BD local
            // donde en la Cookie puede durar anios o ser destruida si la tenemos almcenada en el servidor
            /*const config = {
                headers: {
                    // Al inicio se le coloca Bearer
                    Authorization: 'Bearer Example_TOKEN'
                }
            };

            axios.get('/api/user', config).then(response => {
                console.log(response.data);
            });*/

            // Para consumir los datos que especificamos en 'vue.blade.php' para saber si esta autenticado desde la aplicacion en VUE
            // Lo que vamos a hacer es que ya no nos vamos a comunicar directamente con el servidor enviando peticiones 
            // sino que vamos a consumir lo de 'vue.blade.php'
            /*if(window.Laravel.isLoggedIn){
                // Rellenamos los campos del objeto
                this.isLoggedIn=true;
                this.user=window.Laravel.user;
                this.token=window.Laravel.token;
                // Este seria un primer enfoque que podemos usar
            }
            Esto se comento porque no lo vamos a consumir aqui    
            */

            // Esto es para verificar si tenemos informacion en la Cookie            
            // console.log(this.$cookies.get('auth'));

            /*
                Primero verificamos en el condicional si existe entonces le damos prioridad
                a lo que haya ahi por si hay una actualizacion del lado del servidor o del usuario y
                aprovechamos esos datos para establecerlos cuando realmente los utilizamos y en la cookier
                Como es data que viene del servidor entonces cualquier actualizacion que venga de ahi la 
                podemos aprovechar para actualizar el estado, sobre todo en la parte del usuario por si 
                actualizo sus datos en otra parte 
                
                Aqui se mejoro la condicion porque si no existe el "isLogged In" nos dara error
            */
            if (window.Laravel && window.Laravel.isLoggedIn) {
                this.isLoggedIn = true
                this.user = window.Laravel.user
                this.token = window.Laravel.token

                this.$root.setCookieAuth({
                    isLoggedIn: this.isLoggedIn,
                    token: this.token,
                    user: this.user,
                })
            
            // Si no esta definido entonces no vamos a la cookie, consumimos la cookie, si exite que es el valor
            // que tenemos en el "Logout" entonces hacemos lo mismo que hacemos arriba
            } else {
                const auth = this.$cookies.get('auth')// Obtenemos la cookie que es nuestro almacenamiento de larga duracion

                // Si es valida significa que tenemos algo y por la implementacion que tenemos esto 
                // solo nos regresa algo cuando estas autenticados
                if (auth) {
                    this.isLoggedIn = true
                    this.user = auth.user
                    this.token = auth.token
                    // verification token
                    /*this.$axios.post(this.$root.urls.tokenCheck, {
                        token: auth.token
                    }).then(() => {
                        console.log('tokenCheck')
                    }).catch(() => {
                        this.setCookieAuth('');
                        window.location.href = '/login'
                    })*/
                }
            }
        },
        methods: {
            // Este metodo lo ponemos en el componente Padre para tener mas centralizado las cosas
            // Como en el "UserController" ya le modificamos para que obtenga mas informacion al iniciar sesion
            // creamos esta funcion para recibir esos datos
            setCookieAuth(data){
                // Asi tenemos centralizado la operacion de la Cookie
                // Con set le pasamos por Clave y valor
                this.$cookies.set('auth', data);
            },
            logout(){
                // Llamamos la ruta para cerrar sesion
                // Para poder eliminar el Token le tenemos que pasar a la peticion el correspondiente token del usuario
                // si no le pasaramos el token a la peticion seria por sesion
                // Eso se hara mas adelante para tomar el Token de la sesion en general y no de manera estatica como vimos antes
                
                // Debemos configurar el Header con el Token
                const config = {
                    headers: {
                        Authorization: 'Bearer ' + this.token
                    }
                }

                axios.post('/api/user/logout', null, config)
                .then(() => {
                    // En este caso vamos a hacer una redireccion completa eso es por si tenemos activo
                    // todo el objeto de "window.Laravel" y asi borramos toda la informacion que tiene incluyendo el token
                    // Pero el Token puede estar almacenado de nuestro lado y que todabia exista
                    this.setCookieAuth("");
                    // Esta lineas de codigo se pude comentar para ver el error con la funcion 'dd()' ya que al hacer la redireccion se elimina
                    // el error de la consola
                    window.location.href = '/login';
                })
                .catch(() => {
                    window.location.href = '/login';
                })
            }
        },
        data() {
            return {
                // Aqui vamos a crear un objeto que podamos usar en VUE (El que creamos con el objeto Windows)
                // Este lo podemos guardar de manera local a este componente
                // Empezamos colocando el estado inicial de todos los campos
                isLoggedIn: false,
                user: '',
                token: '',
                 /*
                    Migrar Rutas a App.vue
                        Asi tenemos todas las rutas en un solo lugar y no regadas por toda la app
                        Como es una app pequena se puede hacer en el componente padre y no hay necesidad de
                        hacerlo en un sistema de manejador de estado
                */
                urls: {
                    postUpload: '/api/post/upload',
                    postPatch: '/api/post',
                    postPost: '/api/post',
                    getPostBySlug: '/api/post/slug/',
                    getPostCategories: '/api/category/all',

                }
            }
        }
    }
</script>

<!-- 
    Despues de creados los archivos en la carpeta vue
    Tenemos que crear una plantilla en laravel que luego retornemos desde la lista
    y desde ahi podemos consumir nuestra aplicacion en Vue
        vue.blade.php
-->
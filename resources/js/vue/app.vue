<template lang="">
    <div>
        <!-- Este es un componente -->
        <!-- <list/> -->

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
    // Despues de implementar las rutas ya no hace falta esto
    /*
    import List from "./components/ListComponent.vue" 

    // Le indicamos que vamos a usar este componente
    export default{
        components: {
            List
        }
    }*/

   /*
   Migrar Rutas a App.vue
        Asi tenemos todas las rutas en un solo lugar y no regadas por toda la app
        Como es una app pequena se puede hacer en el componente padre y no hay necesidad de
        hacerlo en un sistema de manejador de estado
   */
    export default {
        data() {
            return {
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
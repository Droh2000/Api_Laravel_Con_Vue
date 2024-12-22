<template lang="">
    <div>
        <h1>Principal</h1>
        <!-- Este es un componente -->
        <list/>
    </div>
</template>

<script>

    import List from "./components/ListComponent.vue" 

    // Le indicamos que vamos a usar este componente
    export default{
        components: {
            List
        }
    }
</script>

<!-- 
    Despues de creados los archivos en la carpeta vue
    Tenemos que crear una plantilla en laravel que luego retornemos desde la lista
    y desde ahi podemos consumir nuestra aplicacion en Vue
        vue.blade.php
-->
// Aqui creamos la aplicacion en Vue 3
import { createApp } from 'vue';

// De este framework vamos a usar los componentes 
import Oruga from '@oruga-ui/oruga-next';

/*
    Vamos a guardar el Token de inicializacion en una Cookie
    Para tenerlo en este almacenamiento de a solo que el usuario lo borre de manera manual
    se puede tener almacenado por un perido de tiempo que configuremos
    Anteriromente se trabajo con Sactum y SPA pero ahora vamos a separarlos entre almacenar los datos del lado
    del servidor con lo que vamos a hacer ahora de almacenar los tokens del lado del cliente
    Ya que como lo tenemos al inicio es que recibimos el token desde el objeto que creamos en el HTML de inicio
    y cuando ya no esta definido en ese objeto, simplemente ya no estamos autenticados
    
    Aqui usaremos un Plugin para manejar las cookies
    
*/
import VueCookies from 'vue3-cookies';

import '../../css/vue.css'; // Importacion del Tailwindcss

// Le agregamos los estilos de oruga Themw
import '@oruga-ui/theme-oruga/dist/oruga.css';
import '@mdi/font/css/materialdesignicons.min.css';

// AXIOS este ya viene por defecto en laravel y es una alternativa a FetchAPI
// Vamos a exponer una propiedad global para que la podamo utilizar en cualquier parte de la app
import axios from 'axios';

// Importamos el componente de arranque (Componente Padre)
import App from './app.vue';

// Para las rutas
import router from './router';

// Creamos la aplicacion mandandole el componente de arranque que es el componente padre de todo
const app = createApp(App);

// Asi se hace para VUE use el paquete
app.use(Oruga).use(router).use(VueCookies);

// Le ponemos el nombre de "$axios" a la propiedad para diferenciarla le ponemos el signo de dolar
app.config.globalProperties.$axios = axios;
// lo exponemos en el objeto windows para JS
window.axios = axios

// Montamos la aplicacion en la pagina vue.blade.php por el ID
app.mount('#app');
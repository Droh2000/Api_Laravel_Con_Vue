// Aqui creamos la aplicacion en Vue 3
import { createApp } from 'vue';

// De este framework vamos a usar los componentes 
import Oruga from '@oruga-ui/oruga-next';
// Le agregamos los estilos de oruga Themw
import '@oruga-ui/theme-oruga/dist/oruga.css';

// AXIOS este ya viene por defecto en laravel y es una alternativa a FetchAPI
// Vamos a exponer una propiedad global para que la podamo utilizar en cualquier parte de la app
import axios from 'axios';

// Importamos el componente de arranque (Componente Padre)
import App from './app.vue';

// Creamos la aplicacion mandandole el componente de arranque que es el componente padre de todo
const app = createApp(App);

// Asi se hace para VUE use el paquete
app.use(Oruga);

// Le ponemos el nombre de "$axios" a la propiedad para diferenciarla le ponemos el signo de dolar
app.config.globalProperties.$axios = axios;
// lo exponemos en el objeto windows para JS
window.axios = axios

// Montamos la aplicacion en la pagina vue.blade.php por el ID
app.mount('#app');
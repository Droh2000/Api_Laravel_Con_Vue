// Aqui creamos la aplicacion en Vue 3
import { createApp } from 'vue';

// Importamos el componente de arranque
import App from './app.vue';

// Creamos la aplicacion mandandole el componente de arranque que es el componente padre de todo
const app = createApp(App);

// Montamos la aplicacion en la pagina vue.blade.php por el ID
app.mount('#app');
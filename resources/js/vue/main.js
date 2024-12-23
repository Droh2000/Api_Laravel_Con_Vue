// Aqui creamos la aplicacion en Vue 3
import { createApp } from 'vue';

// De este framework vamos a usar los componentes 
import Oruga from '@oruga-ui/oruga-next';
// Le agregamos los estilos de oruga Themw
import '@oruga-ui/theme-oruga/dist/oruga.css';

// Importamos el componente de arranque (Componente Padre)
import App from './app.vue';

// Creamos la aplicacion mandandole el componente de arranque que es el componente padre de todo
const app = createApp(App);

app.use(Oruga);

// Montamos la aplicacion en la pagina vue.blade.php por el ID
app.mount('#app');
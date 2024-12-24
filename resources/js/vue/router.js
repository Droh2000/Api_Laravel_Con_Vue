// Crear el ruteo con VUE Router
import { createRouter, createWebHashHistory } from 'vue-router';

// Aqui creamos los componentes
import List from './components/ListComponent.vue';
import Save from './components/SaveComponent.vue';

// Definimos las rutas
const routes = [
    {
        name: 'list', // LE damos un nommbre para poder referenciarlo
        path: '/', // Como la pagina esta desde la raiz entonces colocamos esa misma ruta que tiene en Laravel
        component: List
    },
    {
        name: 'save',
        path: '/',
        component: Save
    }
];

// Creamos las rutas
const router = createRouter({
    history: createWebHashHistory(),
    routes: routes,
});

export default router
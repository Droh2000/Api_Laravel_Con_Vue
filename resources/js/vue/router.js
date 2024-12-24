// Crear el ruteo con VUE Router
import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router';

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
        path: '/save', // Para el otro componente si tendra otra ruta diferente
        component: Save
    }
];

// Creamos las rutas
const router = createRouter({
    history: createWebHistory(), // El que dice Hash le agrega la # en la URL y este es el funcionamiento normal de la URL
    routes: routes,
});

export default router
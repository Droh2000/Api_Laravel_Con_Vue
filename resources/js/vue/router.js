// Crear el ruteo con VUE Router
import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router';

// Aqui creamos los componentes
import List from './components/ListComponent.vue';
import Save from './components/SaveComponent.vue';
import Login from './components/auth/LoginComponent.vue';

// Para obtener la cookie
import { useCookies } from 'vue3-cookies';
const { cookies } = useCookies();

// Definimos las rutas para cada componente
const routes = [
    {
        name: 'list', // LE damos un nommbre para poder referenciarlo
        path: '/', // Como la pagina esta desde la raiz entonces colocamos esa misma ruta que tiene en Laravel
        component: List
    },
    {
        name: 'save',
        // Para el otro componente si tendra otra ruta diferente, Aqui le decimos que va a recibir un parametro
        // que es el SLUG que sera opcional por eso simbolo de interrogacion por tanto debemos verificar si esta en la peticion o no
        path: '/save/:slug?', 
        component: Save
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
];

// Creamos las rutas
const router = createRouter({
    history: createWebHistory(), // El que dice Hash le agrega la # en la URL y este es el funcionamiento normal de la URL
    routes: routes,
});

// Agregar una proteccion adicional (Asi si el usuario esta en Login y quiere ir a otra ruta automaticamente la pagina lo mandara a Login, no podra moverse a otra ruta)
router.beforeEach( async(to, from, next) => {
    // Obtenemos la cookie con el objeto de autenticacion
    const auth = cookies.get('auth');

    // Aqui "login" es el nombre de la ruta que definimos en "main.js"
    if(!auth && to.name !== 'login'){
        // Redireccionamos a login
        return next({name:'login'});
    }

    next();
});

export default router
<template>
    <!-- Vamos a hacer la navegacion entre este componente y el de Save 
        Le pasamos como nombre lo que le especificamos en el router.js 

        Hay que tener en cuenta que la ruta de "save" solo se puede acceder en este caso con este boton
        si lo escribimos directamente en el navegador nos saldra un 404 porque esta ruta no esta definida
        en Laravel
        Si no queremos usar las rutas por nombre podemos poner directamente la URL:
            to={'/save'}
        Con esto tambien hay que fijarnos que al cambiar de paginas no se actualiza la pagina solo cambia la parte donde se
        renderiza el componente (Lo que seria la parte del "router-view")
    
    <router-link :to="{ name:'save' }">Create</router-link>
    -->

    <h1>Post List</h1>

    <!--
        Aqui cambiamos a o-button para implementarlo con oruga
        ademas le implementamos la naegacion de forma manual donde el name es el que esta definido en "router.js"
    -->
    <o-button iconLeft="plus" @click="$router.push({name: 'save'})">Create</o-button>

    <div class="mb-5"></div>
    
    <!--
        <o-button @click="clickMe">Click Me</o-button>

        <o-field label="Email" variant="danger">
            <o-input type="Email" value="example@mail.com"></o-input>
        </o-field>
    -->

    <!-- 
        Metemos los datos obtenidos de la RESP APi a esta tabla 
        En "data" indicamos los datos que estamos trabajando y le especificamos dos puntos
        para que evalue la condicion en JS que le colocamos (Le pasamos la pripeidad lista que tiene los datos)
        ponemos "posts.data" para acceder a los datos de la API ya que por defecto obtenemos los datos con todas las propieades del JSON
        -->
        <o-table :data="posts.data" :loading="isLoading">
        <!-- "filed" tiene que tener el mismo nombre del JSON que obtenemos de respuesta 
            "label" es el como se va a mostrar en la pagina
            "v-slot" es para crear una variable local en este ambito que la usaremos para imprimr la data
        -->
        <o-table-column field="id" label="Id" v-slot="p">
            {{ p.row.id }}
        </o-table-column>
        <o-table-column field="title" label="Title" v-slot="p">
            {{ p.row.title }}
        </o-table-column>
        <o-table-column field="posted" label="Posted" v-slot="p">
            {{ p.row.posted }}
        </o-table-column>
        <o-table-column field="category_id" label="Category" v-slot="p">
            {{ p.row.category.title }}
        </o-table-column>

        <o-table-column field="" label="Actions" v-slot="p">
            <!-- Aqui le vamos a pasar parametros  -->
            <router-link class="mr-3" :to="{ name:'save', params:{ 'slug': p.row.slug } }">Edit</router-link>
            <!-- Este es para la opcion de eliminar -->
                <o-button iconLeft="delete" variant="danger" size="small" rounded @click="deletePost( p )">Delete</o-button>
        </o-table-column>
        </o-table>

        <div class="mb-5"></div>

        <!-- 
        Componente de Paginacion 
        "v-if" -> Es la condicion para cuando solamente lo vamos a mostrar (Si al inicio no tiene datos dara error
                    asi que le ponemos una condicion AND para que pueda verificar la propiedad Lenght donde al inicio
                    verifica si se encuentra definido el arreglo)
        "@change" -> Llamamos la funcion que creamos para actualizar la propiedad de paginacion
        "v-model" -> Es la forma que tenemos para mapear o hacer match entre una propieadad definida en data()
                        con un campo que puede ser un input (o cualquier otro del formulario) y asi obtener su valor
        Definimos los rangos que vamos a mostrar antes o despues 
        "size" -> Le definimos el nombre de la etiqueta HTML
        Le decimos que queremos un diseno simple
        Lo queremos redondeado
        Le decimos que por paginas tome lo que ya tenemos en las propiedades
        -->
        <o-pagination
        v-if="posts.data && posts.data.length > 0"
        @change="updatePage"
        :total="posts.total"
        v-model:current="currentPage"
        :range-before="2"
        :range-after="2"
        size="small"
        :simple="false"
        :rounded="true"
        :per-page="posts.per_page"
        >

        </o-pagination>
</template>
<script>
import { RouterLink } from 'vue-router';


    export default {
        // Aqui colocamos las propiedades que va a tener el componente
        data() {
            return {
                posts: [],// Aqui almacenaremos los datos que vamos a obtener
                isLoading: true,
                // Esta popiedad es para la paginacion
                currentPage: 1,
            }
        },
        // Este metodo forma parte del ciclo de vida de la APP en vue
        // Esta sera una funcion que se ejecuta cuando se monta el componente (Que lo consume el usuario)
        mounted(){
            // Aqui dentro inicializamos los datos del componente (Peticion al API Rest)
            this.listPage();
        },
        // Estos son los metodos que creamos nosotros
        methods:{
            clickMe(){
                // Para usar las propiedades globales lo llamamos con this.
                //console.log(this.$axios);
                alert('Funciono Oruga')
            },
            updatePage(){
                console.log(this.currentPage);
                setTimeout(()=>{
                    this.listPage();
                },100);
            }, 
            listPage(){
                this.isLoading = true;
                // Consumismo la ruta que configuramos en nuestro API de Laravel
                // como son peticiones asyncronas tenemos que usar el Then para manejarlas por los Promesas
                // Dentro del then recibimos la respuesta y despues definimos lo que queremos hacer con esa respuesta
                //
                // Pasamos parametros (?page=VALOR), el valor es la propiedad de paginacion que creamos
                this.$axios.get('/api/post?page='+this.currentPage).then((res) => {
                    // Vemos los datos que tenemos
                    //console.log(res.data.data);

                    // Almacenamos la data en la propiedad del arreglo
                    // Con: "res.data.data" obtenemos el dato directamente de la API
                    // Con: "res.data" Obtenemos la estrctura completa de AXIOS y asi tener acceso a las propieades de paginacion que ya vienen en laravel
                    this.posts = res.data;
                    this.isLoading = false;
                });
            },
            deletePost(row){
                // Para llamar al metodo de eliminar
                // En este para pasarle el valor a la ruta no es tan directo porque tenemos un sistema de ROW creado por ORUGA
                // para encontrar la ruta podriamos colocarle un console.log y asi agregar un punto de interrupcion
                this.$axios.delete('/api/post/'+row.row.id);
                // Aqui eliminamos en la BD pero tambien tenemos los datos almacenados en el arreglo "posts" asi que tenemos que actualizar eso
                // tenemos que eliminar el elemento del arrray (Esta operacion tiene que ser reconocida por el modo reactivo de VUE)
                // Aqui eliminamos por la ubicacion por eso usamos el index
                this.posts.data.splice(row.index, 1);
            }
        }
    }
</script>
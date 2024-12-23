<template>
    <div>
        <h1>Post List</h1>

        <o-button @click="clickMe">Click Me</o-button>

        <o-field label="Email" variant="danger">
            <o-input type="Email" value="example@mail.com"></o-input>
        </o-field>


        <!-- 
            Metemos los datos obtenidos de la RESP APi a esta tabla 
            En "data" indicamos los datos que estamos trabajando y le especificamos dos puntos
            para que evalue la condicion en JS que le colocamos (Le pasamos la pripeidad lista que tiene los datos)
         -->
         <o-table :data="posts">
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
                {{ p.row.category_id }}
            </o-table-column>
         </o-table>

    </div>
</template>
<script>
    export default {
        // Aqui colocamos las propiedades que va a tener el componente
        data() {
            return {
                posts: [],// Aqui almacenaremos los datos que vamos a obtener
                isLoading: true,
            }
        },
        // Este metodo forma parte del ciclo de vida de la APP en vue
        // Esta sera una funcion que se ejecuta cuando se monta el componente (Que lo consume el usuario)
        mounted(){
            // Aqui dentro inicializamos los datos del componente (Peticion al API Rest)
            // Consumismo la ruta que configuramos en nuestro API de Laravel
            // como son peticiones asyncronas tenemos que usar el Then para manejarlas por los Promesas
            // Dentro del then recibimos la respuesta y despues definimos lo que queremos hacer con esa respuesta
            this.$axios.get('/api/post').then((res) => {
                // Vemos los datos que tenemos
                //console.log(res.data.data);

                // Almacenamos la data en la pripiedad del arreglo
                this.posts = res.data.data;
                this.isLoading = false;
            });
        },
        // Estos son los metodos que creamos nosotros
        methods:{
            clickMe(){
                // Para usar las propiedades globales lo llamamos con this.
                //console.log(this.$axios);
                alert('Funciono Oruga')
            }
        }
    }
</script>


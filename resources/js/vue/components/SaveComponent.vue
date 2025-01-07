<template>

    <!-- Este titulo lo mostramos solo si el POSt esta definido sino se muestra el de else-->
     <h1 v-if="post">Update Post <span class="font-bold">{{ post.title }}</span></h1>
     <h1 v-else>Create Post</h1>

    <div class="grid grid-cols-2 gap-3">
        <!-- Como estos campos estan unidos con las varibales de JS se le quito el valor "value"
            ya que el valor por defecto ya esta definido en las variables 
            
            Para mostrar los Errroes
                Vamos a mostrar la variante ':variant=""' -> Segun si tenemos un error o no tenemos un error
                En el que si esta definido le establecemos el estilo correspondiente de "danger" o "primary"
            Con "message" es para mostrar el mensaje
        -->
        <div class="col-span-2">
            <o-field label="Title" :variant="errors.title ? 'danger' : 'primary'" :message="errors.title">
                <o-input v-model="form.title"></o-input>
            </o-field>
        </div>

        <o-field label="Content" :variant="errors.content ? 'danger' : 'primary'" :message="errors.content">
            <o-input v-model="form.content" type="textarea"></o-input>
        </o-field>
        
        <o-field label="Description" :variant="errors.description ? 'danger' : 'primary'" :message="errors.description">
            <o-input v-model="form.description" type="textarea"></o-input>
        </o-field>

        <o-field label="Slug" :variant="errors.slug ? 'danger' : 'primary'" :message="errors.slug">
            <o-input v-model="form.slug"></o-input>
        </o-field>

        <o-field label="Posted" :variant="errors.posted ? 'danger' : 'primary'" :message="errors.posted">
            <o-select v-model="form.posted" placeholder="Select a Option">
                <option value="yes">Yes</option>
                <option value="not">No</option>
            </o-select>
        </o-field>

        <o-field label="Category" :variant="errors.category_id ? 'danger' : 'primary'" :message="errors.category_id">
            <o-select v-model="form.category_id" placeholder="Select a Option">
                <!-- Usamos el listado de categorias que obtenemos con Axios y las mostramos aqui -->
                <option></option>
                <option v-for="c in  categories" v-bind:key="c.id" :value="c.id">{{ c.title }}</option>
            </o-select>
        </o-field>
    </div>

    <div class="mt-3">
        <o-button variant="primary" @click="send">Send</o-button>
    </div>

    <!-- Esto solo lo vamos a mostrar si tenemos un POST -->
    <div class="flex gap-2 mt-5" v-if="post"> 
        <!-- Para mostrar el mensaje si fallan las validaciones se usa primero esto -->
        <o-field :message="fileError" :variant="fileError ? 'danger' : 'primary'">
            <!--
            Componente para subir Archivo
                Le especificamos el modelo "file" que creamos
                El atributo TAG nos activa el boton para poder utilizarlo con el proceso de upload
                    y asi nos muestra la ventana de dialogo
            -->
            <o-upload v-model="file">
                <o-button tag="button-tag" variant="primary">
                    <o-icon icon="upload"></o-icon>
                    <span>Click to upload</span>
                </o-button>
            </o-upload>
        </o-field>

        <o-button icon-left="upload" @click="upload">
            Upload
        </o-button>
    </div>

    <h3>Drag and Drop</h3>

    <o-field :message="fileError" :variant="fileError ? 'danger' : 'primary'">
        <o-upload v-model="filesDaD" multiple drag-drop>
            <section>
                <o-icon icon="upload"></o-icon>
                <span>Drag and Drop area</span>
            </section>
        </o-upload>
    </o-field>

    <!-- Recorremos el archivo ya que puede contener multiples archivos (Asi mostramos el nombre del archivo en la interface al ser subido) --> 
     <span v-for="(file, index) in filesDaD" :key="index">
        {{ file.name }}
     </span>


</template>

<script>
    export default {
        async mounted(){ // Esta se encarga de llamar la funcion

            // Aqui preguntamos si estamos en la fase de Editar o Creacion
            // para esto requerimos acceder al parametro con el $ que es de VUE e indicamos el parametro que queremos obtener
            // "slug" es el metodo que ceamos en el "PostController.php" que busca por el slug
            // Si esto es True entonces es Editar
            if(this.$route.params.slug){
                // Es await porque despues vamos a ejecutar otra tarea que es la de inicializar el POST
                // Con AXIOS buscamos por el SLUG, a la respuesta de peticion le agregamos el parametro Slug
                this.post = await this.$axios.get("/api/post/slug/"+this.$route.params.slug);
                this.post = this.post.data; // Accedemos solo a la Data ya que sabemos como esta compuesta
                // Inicializamos el Post (Por eso es un await porque queremos esperar a obtener la respuesta para poder hacer esta operacion)
                this.initPost();
            }

            // Esto se ejecuta cuando carga el componente
            this.getCategory()
        },
        data(){
            return {
                categories:[],
                post: '', // Para guardar el POST
                // Para mapear una variable con un campo del formulario, dentro definimos cada campo
                form:{
                    title:'',
                    slug:'',
                    description:'',
                    content:'',
                    category_id:'',
                    posted:'',
                },
                // Para manejar los errores (Asi podemos maenjar la logica dentro del catch en el metodo Send())
                errors:{
                    title:'',
                    slug:'',
                    description:'',
                    content:'',
                    category_id:'',
                    posted:'',
                },
                file: null, // Para almacenar archivos que se suban
                fileError: '', // Para mostrar el error al subir el archivo
                filesDaD: [],// Para la parte de subir multiples archivos
            }
        },
        methods:{
            // Para obtener la categoria y mostrar en el campo de "Category"
            // Esta no es asyncrona porque no nos insteresa esperar a termine ya que abajo no hay mas instrucciones a ejecutar
            getCategory(){
                this.$axios.get('/api/category/all').then((res)=>{
                    this.categories = res.data;
                });
            },
            initPost(){
                // Si llenamos en el Script e llena automaticamente en el Template, esto es por VUE
                this.form.title=this.post.title;
                this.form.slug=this.post.slug;
                this.form.description=this.post.description;
                this.form.content=this.post.content;
                this.form.category_id=this.post.category_id;
                this.form.posted=this.post.posted;
            },
            // Metodo para limpiar los errores una vez que si se sumple con la condicion del campo
            cleanErrorsForm(){
                this.errors.title='';
                this.errors.slug='';
                this.errors.description='';
                this.errors.content='';
                this.errors.category_id='';
                this.errors.posted='';
            },
            send(){
                this.cleanErrorsForm();
                // Vamos a verificar si estamos editando o creando
                if(this.post === ''){
                    // Estamos Creando
                    // Este es el metodo es el que cambia que manda a llamar el formulario
                    // Ademas le pasamos el Slug que estamos actualizando que seria como el ID del registro
                    this.$axios.post('/api/post',this.form).then(res => {
                        console.log(res);

                        // Mensaje que se muestra cuando la accion se realizo
                        this.$oruga.notification.open({
                            message: 'Record Create Success',
                            position: 'bottom-right',
                            duration: 4000,
                            closable: true,
                        });

                    }).catch(error => {
                        // Mostramos los errroes que se indican en la ruta si esta definida
                        // Para saber la ruta de acceso de los mensajes se Debugeo el codigo en el navegador en el video del nombr del Commit pertinente
                        if(error.response.data.errors.title){
                            // A cada uno de los camapos le pasamos el mensaje de texto del error
                            this.errors.title = error.response.data.errors.title[0];
                        }
                        if(error.response.data.errors.slug){
                            this.errors.slug = error.response.data.errors.slug[0];
                        }
                        if(error.response.data.errors.description){
                            this.errors.description = error.response.data.errors.description[0];
                        }
                        if(error.response.data.errors.posted){
                            this.errors.posted = error.response.data.errors.posted[0];
                        }
                        if(error.response.data.errors.content){
                            this.errors.content = error.response.data.errors.content[0];
                        }
                        if(error.response.data.errors.category_id){
                            this.errors.category_id = error.response.data.errors.category_id[0];
                        }

                    });
                }else{
                    // Estamos Editando
                    // Este es el metodo que manda a llamar el formulario
                    this.$axios.patch('/api/post'+this.post.id,this.form).then(res => {
                        console.log(res);

                        // Mensaje que se muestra cuando la accion se realizo
                        this.$oruga.notification.open({
                            message: 'Record Update Success',
                            position: 'bottom-right',
                            duration: 4000,
                            closable: true,
                        });

                    }).catch(error => {
                        // Mostramos los errroes que se indican en la ruta si esta definida
                        // Para saber la ruta de acceso de los mensajes se Debugeo el codigo en el navegador en el video del nombr del Commit pertinente
                        if(error.response.data.errors.title){
                            // A cada uno de los camapos le pasamos el mensaje de texto del error
                            this.errors.title = error.response.data.errors.title[0];
                        }
                        if(error.response.data.errors.slug){
                            this.errors.slug = error.response.data.errors.slug[0];
                        }
                        if(error.response.data.errors.description){
                            this.errors.description = error.response.data.errors.description[0];
                        }
                        if(error.response.data.errors.posted){
                            this.errors.posted = error.response.data.errors.posted[0];
                        }
                        if(error.response.data.errors.content){
                            this.errors.content = error.response.data.errors.content[0];
                        }
                        if(error.response.data.errors.category_id){
                            this.errors.category_id = error.response.data.errors.category_id[0];
                        }

                    });
                }
            },
            // Metodo para subir una imagen 
            upload(){
                // Limpiamos los errores para cuando se envue otra vez no se quede con el error anterior
                this.fileError='';
                // Como vamos a enviar una peticion de tipo formulario pero esta viene siendo con el formato JavaScript
                // Las peticiones que hemos hecho antes son diferentes a enviarlas mediante un formulario
                const formData = new FormData();
                // A este objeto le indicamos el campo que es 'image' ya que el POSt va por la ruta
                formData.append('image', this.file);
                // Luego hacemos la peticion con el Axios y aqui le pasamos la data que no es el JSON sino el FormData
                this.$axios.post('/api/post/upload/'+this.post.id, formData, {
                    // Especificamos las opciones de envio
                    headers: {
                        'Content-Type' : 'multipart/form-data' // Aqui especificamos que es de formulario
                    }
                // El Then es cuando nos regrese todo OK
                }).then((res) => {
                    console.log(res);
                }).catch((error) => {
                    this.fileError=error.response.data.message;
                    console.log(error);
                });
            }
        },
        // Se coloca afuera el apartado de Drag end Drop para que funcione
        watch:{
            // vamos a observar la propiedad (asi la leemos) que definimos antes
            filesDaD: {
                // Esta es logica de VUE y aqui vamos a obtener el valor
                handler(val){ // Dentro de VAL tenemos el array para acceder al archivo
                    this.fileError='';
                    const formData = new FormData();
                    // En este caso lo hacemos con el 'val' y asi hacemos referencia al ultimo elemento cargado
                    formData.append('image', val[val.length-1]);
                    this.$axios.post('/api/post/upload/'+this.post.id, formData, {
                        headers: {
                            'Content-Type' : 'multipart/form-data'
                        }
                    }).then((res) => {
                        console.log(res);
                    }).catch((error) => {
                        this.fileError=error.response.data.message;
                        console.log(error);
                    });
                },
                // Para observar objetos o array
                deep: true
            }
        }
    }
</script>
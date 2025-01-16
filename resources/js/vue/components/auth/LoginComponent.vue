<template>

    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-md">
            <!-- Aqui prevenimos que tenga el comportamiento por defecto del formulario y le pasamos el metodo 'submit' -->
            <form @submit.prevent="submit">
                <o-field label="Username" :variant="errors.login ? 'danger' : 'primary'" :message="errors.login">
                    <o-input v-model="form.email"></o-input>
                </o-field>
                <o-field label="Password" :variant="errors.login ? 'danger' : 'primary'" :message="errors.login">
                    <o-input v-model="form.password" type="password"></o-input>
                </o-field>
                <!-- Al boton le indicamos con "native-type" para que pueda interceptar el submit 
                    Le agregamos la parte de disabledBotton para activar o desactivar el boton 
                -->
                <o-button @click="submit" :disabled="disabledBotton" variant="primary" native-type="submit" class="float-right">Send</o-button>
            </form>
        </div>
    </div>

</template>
<script>
export default {
    // Esta funcion la colocamos aqui porque es parte del ciclo del vida del componente
    // es para que si el usuario estaa autenticado no pueda ir a la pagina de Login poniendo la ruta directamente sino que solo se pueda Cerrando Sesion
    // Podriamos usar VUE router pero vamos a usar una validacion Local
    created() {
        // Cuando se crea la aplicacion podemos hacer alguna verificaciones
        // Aqui verificamos por el objeto que tenemos en el "App.vue" que esta dentro de la condicional
        // ya que este es el componente raiz que definimos en el main.js que es el primero que se carga y despues de ahi se cargan el resto de los componentes con el Router-view
        // Entonces podemos acceder a eses ccomponente y al final a sus metodos y propiedades
        // usamos $root para acceder a variables propias del Framework o los plugins que instalamos
        // y ponemos el nombre del metodo o propiedad que queramos acceder
        if (this.$root.isLoggedIn) { // Verificamos si estamos autenticados
            // Podriamos hacer una carga de toda la pagina pero en este caso no hace falta asi que en lugar de eso
            // vamos a hacer una redireccion local empleando el objeto del "router"
            // Usamos "push" ya que las rutas se manejan como un historial donde se van colocando como en una cola
            // Donde la mas arriba es la que se estaria visualizando que la colocamos aqui encima de la pila
            // Como empleamos nombres a nuestras Rutas en el "router.js" queremos ir a la de "list"
            this.$router.push({ name: 'list' })
        }
    },
    data() {
        return {
            disabledBotton:false, // Ponemos esto para que el usuario no pueda iniciar sesion varias veses
            form: {
                // Estos son los datos que ya estan registrados en la BD
                // Los datos de ingreso los ponemos aqui para no estar escribiendolos
                email: 'admin@admin.com',
                password: '12345',
            },
            errors: {
                login: ''
            },
        }
    },
    methods: {
        // Esta funcion es para limpiar los errores que se muestran en el formulario
        cleanErrorsForm() {
            this.errors.login = ''
        },
        // Metodo para ingresar los datos del Login
        submit() {
            this.cleanErrorsForm()
            this.disabledBotton=true
            axios.get('sanctum/csrf-cookie').then(response => {
                axios.post('/api/user/login', this.form).then(response => {
                    // Aqui le establecemos los datos que requiere para establecerle la cookie
                    // Le pasamos el objeto completo
                    this.$root.setCookieAuth({
                        isLoggedIn: response.data.isLoggedIn,
                        token: response.data.token,
                        user: response.data.user,
                    });

                    /*
                        Una vez iniciada la sesion deberiamos de redireccional pero tambien queremos mostrar el mensaje de Oruga
                        Lo que vamos a hacer aqui (VUE solo cambia la parte donde se esta renderizando en DIV id=app)
                        y toda la demas parte del HTML no se va actualizar, esto podriamos sacarlo como ventaja en el donde
                        tenemos en el "vue.blade.php" laravel tiene que llamar lo del condicional "if(Auth::check)"

                        Cuando se autentica tenemos que recargar toda la pagina y no solo de una porcion por tanto no podemos
                        emplear la navegacion de VUE router porque no se cargaria lo de "vue.blade.php"
                        Por tanto aqui tenemos que hacer una redireccion empleando JS nativo
                        En 'window.location.href' indicamos que queremos ir hacia la pagina principal de la aplicacion
                        La redireccion la encerramos en "setTimeout" para que se espere 1 segundo y medio y asi alcanzar a mostrar la 
                        notificacion
                        Tambien por proteccion podemos en ese lapso de tiempo bloquear el boton 
                        Con esto recargamos la pagina y asi Laravel va a crear el objeto que definimos en 'vue.blade.js'
                    */
                    setTimeout(() => (window.location.href = '/'), 1500); //  Despues de la implementacion de las cookies ya no hace flata hacer una recarga completa porque no empleamos el de la session
                    // this.disabledBotton = false
                    
                    // Mostramos la notificacion de exito
                    this.$oruga.notification.open({
                        message: 'Login success',
                        position: 'bottom-right',
                        duration: 1000,
                        closable: true
                    });

                }).catch(error => {
                    // Capturamos los errores en el formulario
                    // Aqui podriamos implementar condicionales para mostrar mejor el error si es un campo u otro
                    this.disabledBotton=false;
                    this.errors.login = error.response.data;
                })
            })

        },
    }
}
// Despues de creado el componente definimos la ruta en el archivo 'api.php'
// Pero tambien lo registramos en el archivo "route.js"
</script>
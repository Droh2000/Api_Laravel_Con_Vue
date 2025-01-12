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
                <!-- Al boton le indicamos con "native-type" para que pueda interceptar el submit -->
                <o-button :disabled="disabledBotton" variant="primary" native-type="submit" class="float-right">Send</o-button>
            </form>
        </div>
    </div>

</template>
<script>
export default {
    created() {

        if (this.$root.isLoggedIn) {
            this.$router.push({ name: 'list' })
        }
    },
    data() {
        return {
            disabledBotton:false,
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

                    this.$root.setCookieAuth({
                        isLoggedIn: response.data.isLoggedIn,
                        token: response.data.token,
                        user: response.data.user,
                    })

                    setTimeout(() => (window.location.href = '/'), 1500)
                    // this.disabledBotton = false
                    
                    // Mostramos la notificacion de exito
                    this.$oruga.notification.open({
                        message: 'Login success',
                        position: 'bottom-right',
                        duration: 1000,
                        closable: true
                    })

                }).catch(error => {
                    // Capturamos los errores en el formulario
                    // Aqui podriamos implementar condicionales para mostrar mejor el error si es un campo u otro
                    this.disabledBotton=false
                    this.errors.login = error.response.data
                })
            })

        },
    }
}
// Despues de creado el componente definimos la ruta en el archivo 'api.php'
// Pero tambien lo registramos en el archivo "route.js"
</script>
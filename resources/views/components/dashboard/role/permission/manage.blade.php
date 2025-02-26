<div>
    <h3>Permission</h3>
    <ul id="permissionListRol">
        <!-- Recorremos con un bucle para obtener un listado de todos los permisos -->
        @foreach ($permissionsRole as $p)
            <li>{{ $p->name }}</li>
        @endforeach
    </ul>

    <!-- Formulario para asignar permisos al Rol 
        La ruta en "route()" es la ruta que especificamos en web.php
        Dentro del Select iteramos todos los permisos que ya se los pasamos a la vista en el Manage.php

        Este formulario ya no lo ocupamos porque empleamos mejor el uso de Axios con Javascript para que 
        no se recarge la pagina cada vez que se selecciona una opcion con el selectors
    -->
    <h3>Assign</h3>
    {{--<form action="{{ route('role.assign.permission', $role->id) }}" method="post">--}}
    @csrf
    <select name="permission">
        @foreach ($permissions as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    </select>
    <button type="button" id='buttonAssignPermission'>Send</button>
    {{--</form>--}}
</div>

<script>
    // Capturamos el evento click del boton por el ID que le agregamos al boton
    document.getElementById('buttonAssignPermission').addEventListener('click', function() {
        // A la funcion le pasamos el rol inyectandole codigo de PHP entre las dos llaves
        // Como es un entero y no un texto no lo ponemos entre comillas
        // Laravel se encarga de convertir todo su codigo a PHP y de ahi el servidor se encarga de regresarlo al cliento
        // por lo que al final esto se trauduce en solo HTML y JS
        // Pero igual siempre hay que tener cuidad de estas implementaciones y saber en que punto se van a ejecutar
        assignPermissionToRol({{ $role->id }})
    })

    function setListenerToDeletePermission() {
        document.querySelectorAll('#permissionListRol button').forEach(b => {
            b.addEventListener('click', function() {
                let perId = b.getAttribute('data-per-id')

                axios.post('{{ route('role.delete.permission', $role->id) }}', {
                    'permission': perId
                }).then((res) => {
                    document.querySelector('.per_' + perId).remove()
                })
            })
        });
    }

    setListenerToDeletePermission()

    // Funcion elegir permiso al Rol
    function assignPermissionToRol(roleId) {

        // Aqui almacenamos la refrencia del elemento del selector en el HTML
        const perId = document.querySelector('select[name="permission"]').value
        // Como puede que no este ningun elemento seleccionado en el selector puede que nos de nulo asi que verificamos primero
        if (document.querySelector('.per_' + perId) !== null) {
            return alert('Permission already assigned')
        }

        // llamada con Axios
        axios.post('{{ route('role.assign.permission', $role->id) }}', {
            // Le pasamos la Data  que seria una referencua al Select con su valor
            'permission': perId
        }).then((res) => {
            // Cuando tenemos el elemento del selector hay que inyectar a la pagina 
            // Aqui dentro le establecemos nuevo contenido para esto le concatenamos el contenido que ya tenia antes "+="
            // Dentro de las comillas le colocamos el nuevo contenido
            // Un problema que teniamos al incio es que teniamos es que podemos agregar permisos que ya teniamos, este no se coloca en la BD
            // pero si sale reflejado en la pagina (Se puede verificar esto con una condicional o preguntar antes si ya tenemos el permiso en 
            // el metodo Handle) de la clase "Manage.php"
            document.querySelector('#permissionListRol').innerHTML += ` 
                    <li class="per_${ res.data.id }">
                        ${res.data.name}
                        <button type="button" data-per-id="${ res.data.id }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </li>
                `

            setListenerToDeletePermission()
        })
    }

    /*
        Vamos a consumir el permiso de "$permission" para agregarlo al listado de manera dinamica mediante JS
        para esto le agregarmos un identificador al lista de <ul> luego de ahi lo podemos seleccionar, creamos el elemento HTML con JS
        luego le establecemos el contenido 
    */
</script>
<div>
    <div class="card card-gray">
        <h3>Permission</h3>
        <ul id="permissionListRol">
            <!-- Recorremos con un bucle para obtener un listado de todos los permisos -->
            @foreach ($permissionsRole as $p)
                <!-- Esta clase es para poder eliminar facilmente el li por medio del identificador -->
                <li class="per_{{ $p->id }} item-list">
                    <!-- Este formulario se creo para poder eliminar los permisos que ya se tengan asignados 
                        Estas lineas estan comentadas porque se copio del ejemplo en el que ya esta dapatado para JS con AXIOS
                    -->    
                    {{-- <form action="{{ route('role.delete.permission', $role->id) }}" method="post">
                    @method('delete')
                    @csrf
                    <!-- El nombde dado en "name" tiene que ser el mismo que el especificado en la funcion "delete" en "Manage.php" en el Request -->
                    <input type="hidden" name="permission" value="{{ $p->id }}"> --}}
                    {{ $p->name }}
                    {{-- <button type="submit"> --}}
                    <!-- Aqui creamos un atributo personalizado en el cual en el boton estamos colocando el identificador del permiso que queremos eliminar -->
                    <button type="button" data-per-id="{{ $p->id }}" class="btn-sm btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                    {{-- </form> --}}

                </li>
            @endforeach
        </ul>
    </div>
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

    // El metodo de eliminar se creo asi aparte ya que aqui obtenemos con el selector de ALL para hacer un bucle y establecer el evento click
    // para la eliminacion, porque cuando asignamos un permiso y le damos al boton de SEND no tiene sentido ejecutar esta funcion al momento de cargar la pagina
    // porque el permiso se esta creando despues entonces no tendria el este evento para eliminar (Esta funcion se tiene que ejecutar cada vez que se crea un 
    // permiso de manera manual)
    function setListenerToDeletePermission() {
        // Buscamos por el boton que es al que le queremos agregar el Listener indicando tambien el ID
        // Tenemos que recorrer este conjunto en un Bucle ya que no podemos asignar el evento a todos sino uno por uno
        document.querySelectorAll('#permissionListRol button').forEach(b => {
            // A cada elementos le agregamos el evento
            b.addEventListener('click', function() {
                // Primero obtenemos la referencia del ID del permiso que queremos eliminar y con la funcion de "getAttribute"
                // le colocamos el nombre del atributo personalizado que le agregamos NO EL CONTENIDO
                let perId = b.getAttribute('data-per-id')

                // Aqui no usamos el DELETE ya que tenemos el problema que no podmeos pasar datos con el Request
                // porque en el Body no podemos colocar nada por lo tanto lo que podemos hacer es pasarlo por POST
                // A este le pasamos la RUTA que definimos en Blade y su correspondiente ID
                axios.post('{{ route('role.delete.permission', $role->id) }}', {
                    'permission': perId // Le pasamos la Data por el ID
                }).then((res) => {
                    // Eliminamos el ITEM del listado (Implementacion del eliminado del permiso)
                    // Ponemos un . al inicio porque estamos buscando por una clase
                    document.querySelector('.per_' + perId).remove()
                })
            })
        });
    }
    // Para que se pueda mandar a llamar la funcion y le defina el Evento a todos los permisos que existen actualmente
    setListenerToDeletePermission()

    // Funcion elegir permiso al Rol
    function assignPermissionToRol(roleId) {

        // Aqui almacenamos la refrencia del elemento del selector en el HTML
        // Buscamos si el permiso ya existe antes de intentarlo agregar
        const perId = document.querySelector('select[name="permission"]').value
        // Como puede que no este ningun elemento seleccionado en el selector puede que nos de nulo asi que verificamos primero
        // Esto se hizo para Evitar insertar permisos repetidos al listado (Estos si se agregan a la interface pero no en la BD por 
        // la logica implementada, asi que solo hay que evitar que se agregen al listado ai ya estan)
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
            // A este contenido le colocamos los atributos para asignarle su ID y nombre de su correspondiente Atributo (Esto lo definimos para eliminar el permiso)
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
            // llamamos el meotodo otravez para que se reflejen los cambios y agregar los eventos a los permisos que se crean
            // Puede pasar que lo de Colocar informacion HTML sea mas lenta y se llame primero esta funcion porlo que no tomaria los elementos nuevos
            // esto se pueede solucionar colocando un Time para asegurarnos que se ejectue cuando ya este el conenido
            setListenerToDeletePermission()
        })
    }

    /*
        Vamos a consumir el permiso de "$permission" para agregarlo al listado de manera dinamica mediante JS
        para esto le agregarmos un identificador al lista de <ul> luego de ahi lo podemos seleccionar, creamos el elemento HTML con JS
        luego le establecemos el contenido 
    */
</script>
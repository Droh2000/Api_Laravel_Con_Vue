<div>
    <h3>Permission</h3>
    <ul>
        <!-- Recorremos con un bucle para obtener un listado de todos los permisos -->
        @foreach ($permissionsRole as $p)
            <li>{{ $p->name }}</li>
        @endforeach
    </ul>

    <!-- Formulario para asignar permisos al Rol 
        La ruta en "route()" es la ruta que especificamos en web.php
        Dentro del Select iteramos todos los permisos que ya se los pasamos a la vista en el Manage.php
    -->
    <h3>Assign</h3>
    <form action="{{ route('role.assign.permission', $role->id) }}" method="post">
    @csrf
    <select name="permission">
        @foreach ($permissions as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    </select>
    <button type="button" id='buttonAssignPermission'>Send</button>
    </form>
</div>
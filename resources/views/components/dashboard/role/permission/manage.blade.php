<div>
    <h3>Permission</h3>
    <ul>
        <!-- Recorremos con un bucle para obtener un listado de todos los permisos -->
        @foreach ($permissionsRole as $p)
            <li>{{ $p->name }}</li>
        @endforeach
    </ul>
</div>
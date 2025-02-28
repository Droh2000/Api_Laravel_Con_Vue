@extends('dashboard.master')

@section('content')
     <!-- De los posts solo vamos a mostrar titulo pero no el contenido porque es mucho dato 
    
        En la tabla podemos agregar las opciones CRUD al lado del registro o mas opciones
        Por eso se agrega la columna de las opciones

        La directiva "can" es para indicarle el perimos que debe de tener el usuario y dentro lo que se mostrara o no
        segun si el usuario cumple o no el permisos
    -->
    @can('editor.post.create')
        <a class="btn btn-primary my-3" href="{{ route('post.create') }}" target="blank">Create</a>
    @endcan
    <table class="table">
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Title
                </th>
                <th>
                    Posted
                </th>
                <th>
                    Category
                </th>
                <th>
                    Options
                </th>
            </tr>
            
        </thead>
        <tbody>
            @foreach ($posts as $p)
                <tr>
                    <td>
                        {{ $p->id }}
                    </td>
                    <td>
                        {{ $p->title }}
                    </td>
                    <td>
                        {{ $p->posted }}
                    </td>
                    <td>
                        <!-- Por la relacion que implementamos en el modelo nos sale por defecto toda la informacion de la
                            categoria, asi que solo obtenemos el campo que nos interesa -->
                        {{ $p->category->title }}
                    </td>
                    <td>
                        <a class="btn btn-success mt-2" href="{{ route('post.show',$p) }}">Show</a>
                        <!-- Para el de eliminar tenemos que hacerlo de otra forma porque asi no va funcion, se tiene que hacer por
                             que es con un formulario porque no es una peticion de tipo GET
                            
                             Con este CAN protegemos los botones para que solo salgan si se tiene los permisos 
                        -->
                        @can('editor.post.update')
                            <a class="btn btn-success mt-2" href="{{ route('post.edit',$p) }}">Edit</a>
                        @endcan
                        @can('editor.post.destroy')
                        <form action="{{ route('post.destroy', $p) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger mt-2" type="submit">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- 
        PARA LA PAGINACION

        Ya implementada en el controlador con esto creamos la forma de navegar entre paginas
        Este componente por defecto usa clases de CSS Tawing
    -->
    <div class="mt-2"></div>
    {{ $posts->links() }}

@endsection
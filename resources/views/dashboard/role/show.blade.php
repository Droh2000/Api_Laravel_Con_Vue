@extends('dashboard.master')

@section('content')
     <h1>{{ $role->name }}</h1>
     <!-- Aqui implementamos el componenete que nos lista los permisos 
          En su uso siempre empezamos con x- y poniendo los nombres de las carpetas separandolas por un punto
          sin importar si los nombres estan en mayusculas o minusculas

          Le suministramos el "role" y como queremos que evalue el atribute le colocamos los dos puntos al inicio
     -->
     <x-dashboard.role.permission.manage :role="$role" />
@endsection
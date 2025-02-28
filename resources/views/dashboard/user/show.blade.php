@extends('dashboard.master')

@section('content')
     <h1>{{ $user->name }}</h1>
     <!-- Este es el componente donde Gestionamos los Roles de los Usuarios -->
     <x-dashboard.user.role.permission.manage :user='$user'/>
@endsection
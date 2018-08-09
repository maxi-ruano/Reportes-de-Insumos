@extends('layouts.templeate')
@section('titlePage', 'Estimado usuario:')

@section('content')
    <h2><span class='red'> Se ha producido un error en el sistema! </span> </h2>
    Por favor, comuniquese con el Administrador del sistema.
    <p>Si deseas ir a la página de inicio haga <a href="{{ url('login')}}" class="blue"> click aqui</a> </p>
@endsection
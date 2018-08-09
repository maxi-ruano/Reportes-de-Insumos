@extends('layouts.templeate')
@section('titlePage', 'Estimado usuario:')

@section('content')
    <h2 class='red'>La URL que está intentando ingresar NO ES VÁLIDA!</h2>

    <p> Error 404: Indica que la página que se está tratando de cargar no se ha encontrado. <br>
        Esto puede ocurrir porque hemos escrito mal la dirección de la página web que queremos cargar, que hemos cargado la página a través de un enlace erróneo, o que la página sí que existió en su momento pero ahora ya no.</p>

    Por favor, para ir a la página de inicio haga <a href="{{ url('login')}}" class="blue"> click aqui</a>
@endsection
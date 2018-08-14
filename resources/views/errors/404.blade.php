@extends('layouts.app')

@section('content')
    <div style="max-width:600px; margin:0px auto;">   
        <h2><b>Estimado usuario:</b></h2>
        <h3 style='color:red'>La URL que está intentando ingresar NO ES VÁLIDA!</h3>

        <p> Error 404: Indica que la página que se está tratando de cargar no se ha encontrado. <br>
            Esto puede ocurrir porque hemos escrito mal la dirección de la página web que queremos cargar, que hemos cargado la página a través de un enlace erróneo, o que la página sí que existió en su momento pero ahora ya no.</p>

        Por favor, para ir a la página de inicio haga <a href="{{ url('login')}}"> click aqui</a>
    </div>
@endsection
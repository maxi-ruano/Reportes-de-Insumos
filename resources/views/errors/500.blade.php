@extends('layouts.app')

@section('content')
    <div style="max-width:600px; margin:0px auto;">   
        <h3><b>Estimado usuario:</b></h3>
        <h3 style='color:red'>Se ha producido un error en el sistema!</h3>

        <p> Error 500: Indica un error interno, lo que se puede traducir que la página web tiene algún error en el código, por lo que el servidor no puede generar el código HTML.</p>

        Por favor, para ir a la página de inicio haga <a href="{{ url('login') }}"> click aqui</a>
    </div>
@endsection
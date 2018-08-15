@extends('layouts.templateErrores')

@section('content')
    <h1>ERROR 405</h1>
    <h3 style='color:#ffd300;'>El método que está intentando usar NO es permitido!</h3>

    <p> Indica que la solicitud que especifica el método HTTP fue recibida y reconocida por el servidor, pero el servidor ha rechazado este método en particular para el recurso solicitado</p>

@endsection

@extends('layouts.templeate')
@section('titlePage', 'Bienvenido')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dirección General de Habilitación de Conductores y Transporte</div>

                <div class="panel-body">
                    <p>Sistema para Tramites Habilitados </p>
                    Usuario: <h2> {{ Auth::user()->name }} </h2>
                    E-mail: <h2> {{ Auth::user()->email }} </h2>
                    Tipo de Usuario: <h2> {{ Auth::user()->roles->first()->name }} </h2>
                    Sucursal: <h2> {{ Auth::user()->sucursal }} </h2>
                </div>
            </div>    
        </div>
    </div>
</div>
@endsection

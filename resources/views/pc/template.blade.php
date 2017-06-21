@extends('layouts.templeate')
@section('titlePage', 'Clientes')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ isset($customer) ? "Editar" : "Crear" }} Cliente</h2>
          @include('includes.headerContainer')
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if( isset($teoricopc) )
            {{ Form::open(['route' => ['computadoras.update', $teoricopc], 'method' => 'PUT', 'role' => 'form', 'files' => false]) }}
        @else
        {{ Form::open(['route' => 'computadoras.store', 'method' => 'POST', 'role' => 'form', 'files' => false]) }}
        @endif
                <p>ip</p>
                <input type="text" name="ip" value="{{ isset($teoricopc) ? $teoricopc->ip : null }}">
                <p>nombre</p>
                <input type="text" name="name" value="{{ isset($teoricopc) ? $teoricopc->name : null }}">
                <p>sucursal_id</p>
                <input type="text" name="sucursal_id" value="{{ isset($teoricopc) ? $teoricopc->sucursal_id : null }}">
                <p>estado</p>
                <input type="text" name="estado" value="{{ isset($teoricopc) ? $teoricopc->estado : null }}">
                <input type="submit" value="enviar">
        {{ Form::close() }}
      </div>
    </div>
  </div>
</div>

<!-- /page content -->
@endsection

@section('scripts')
<!-- validator -->
<script src="{{ asset('vendors/validator/validator.js')}}"></script>
@include('includes.scriptForms')

@endsection

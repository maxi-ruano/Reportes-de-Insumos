@extends('layouts.templeate')
@section('titlePage', 'Justificacion')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ isset($justificado) ? "Editar" : "" }} Justificacion</h2>
          @include('includes.headerContainer')
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
            {{ Form::open(['route' => 'justificaciones.store', 'method' => 'POST', 'role' => 'form', 'files' => false, 'class' => 'form-horizontal']) }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sucursal
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->sucursal }}' type="text"  class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Usuario
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->userName }}' type="text" name="last-name"  class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Utlimo insumo correcto</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->insumo_ultimo }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Insumo Fallo</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->insumo_intento_insercion }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de registro</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->created_at }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Justificado</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->justificado }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Usuario Justicicion</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->user_justificacion }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">fecha Justicicion</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->fecha_justificacion }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Narrativa Justicicion</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input value='{{ $controlSecuenciaInsumos->justificacion }}' class="form-control col-md-7 col-xs-12" type="text" name="middle-name" disabled>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <a href="{{ route('reporteSecuenciaInsumos') }}" class="btn btn-primary" type="button">Volver a Reporte</a>
              </div>
            </div>
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

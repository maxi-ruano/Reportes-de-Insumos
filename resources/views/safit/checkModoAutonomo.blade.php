@extends('layouts.templeate')
@section('titlePage', 'Revisar Pre-Check')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          @include('safit.botoneraPrecheck')
          <div class="clearfix"></div>
      </div>
      <div class="x_content">
        {!! Form::open(['route' => 'consultarPreCheck', 'id'=>'consultarPreCheck', 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form', 'files' => true ]) !!}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Numero Documento<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="nro_doc" aria-describedby="NumeroDeDocumento" placeholder="Ejem ... 54468798">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nacionalidad<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('nacionalidad', $paises,   null, ['id'=>'paises', 'data-type'=>'text', 'class'=>'select2_single form-control select2 paises', 'tabindex'=>'-1', 'data-placeholder'=>'Seleccionar Cliente', 'required']) !!}

              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo Documento<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('tipo_doc', $tdoc,   null, ['data-type'=>'text', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'data-placeholder'=>'Seleccionar Cliente', 'required']) !!}
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sexo<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('sexo', $sexo,   null, ['data-type'=>'text', 'class'=>'select2_single form-control', 'data-placeholder'=>'Seleccionar Cliente', 'required']) !!}
              </div>
            </div>
            </fieldset>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">Buscar Boleta Pago</button>
              </div>
            </div>
        {{ Form::close() }}
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>

<!-- /page content -->
@endsection

@push('scripts')
  <script src="{{ asset('vendors/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <script>
    $( document ).ready(function() {
      $(".select2").select2({
        allowClear: true,
        language: "es"
      });
      $(".paises").select2({
        data: paises,
        placeholder: "Nacionalidad"
      });
    });
  </script>
  <script src="{{ asset('vendors/select2/dist/js/select2.full.min.js')}}"></script>
  <script src="{{ asset('vendors/validator/validator.js')}}"></script>
@endpush

@section('css')
<!-- Select2 -->
    <link href="{{ asset('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

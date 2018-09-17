@extends('layouts.templeate')
@section('titlePage', 'Boletas de Pago - SAFIT')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

      <div class="x_content">
        {!! Form::open(['route' => 'consultarBoletaPagoPersona', 'id'=>'consultarBoletaPagoPersona', 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form', 'files' => true ]) !!}
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::select('tipo_doc', $tipodocs, 1, ['class' => 'form-control']) !!}
                </div>
            </div>    
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Documento<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" class="form-control" name="nro_doc" aria-describedby="nroDocumento" required = 'true' placeholder="Ejem ... 34125452">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">Buscar Boleta de Pago</button>
              </div>
            </div>
        {{ Form::close() }}
        <div class="clearfix"></div>
        
        @if (isset($boleta))
            <p>EXISTEN BOLETAS... </p>
        @endif
    
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            @if (isset($error))
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>{{ $error }}</strong>
              </div>
            @endif
            @if (isset($success))
              <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>{{ $success }}</strong>
              </div>
            @endif
          </div>
        </div>
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
  <!-- Custom Theme Scripts -->
  <script src="{{ asset('build/js/custom.min.js')}}"></script>
@endpush
@extends('layouts.templeate')
@section('titlePage', 'Generar Cenat')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <h2>Generar Cenat </h2>
          <div class="clearfix"></div>
      </div>
      <div class="x_content">
        {!! Form::open(['route' => 'consultarBoletaPago', 'id'=>'consultarBoletaPago', 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form', 'files' => true ]) !!}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Numero de Boleta<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="bop_cb" aria-describedby="emailHelp" placeholder="Ejem ... 1065468798">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Centro Emisor<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="cem_id" class='select2_single form-control' data-placeholder='Seleccionar Centro Emisor'>
                @foreach ($centrosEmisores as $key => $value)
                    <option value="{{ $value->safit_cem_id }}"> {{ $value->name }} </option>
                @endforeach
                </select>
              </div>
            </div>
            </fieldset>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="YYYY-MM-DD">
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">Buscar Boleta Pago</button>
              </div>
            </div>

        {{ Form::close() }}
        <div class="clearfix"></div>
        @if (isset($boleta))
          {!! Form::open(['route' => 'generarCenat', 'id'=>'generarCenat', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
              {!! Form::hidden('nro_doc', isset($boleta) ? $boleta->nro_doc : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('tipo_doc', isset($boleta) ? $boleta->tipo_doc : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('sexo', isset($boleta) ? $boleta->sexo : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('nombre', isset($boleta) ? $boleta->nombre : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('apellido', isset($boleta) ? $boleta->apellido : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_id', isset($boleta) ? $boleta->bop_id : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_cb', isset($boleta) ? $boleta->bop_cb : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_monto', isset($boleta) ? $boleta->bop_monto : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_fec_pag', isset($boleta) ? $boleta->bop_fec_pag : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('cem_id', isset($boleta) ? $boleta->cem_id : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('nacionalidad', null, ['class' => 'form-control']) !!}
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre : {{ $boleta->nombre }} {{ $boleta->apellido }}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label>Numero Documento : {{ $boleta->nro_doc }}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Fecha Nacimiento :<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="fecha_nacimiento" type="text" data-date-format='yy-mm-dd' class="form-control has-feedback-left" id="single_cal4" placeholder="First Name" aria-describedby="inputSuccess2Status4">
                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                </div>
              </div>
              </fieldset>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block">Generar Certificado Virtual</button>
                </div>
              </div>
          {{ Form::close() }}
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
  <!-- bootstrap-daterangepicker -->
  <script src="{{ asset('vendors/moment/min/moment.min.js')}}"></script>
  <script src="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <!-- Custom Theme Scripts -->
  <script src="{{ asset('build/js/custom.min.js')}}"></script>
  <script>
    $('#single_cal4').daterangepicker({
      singleDatePicker: true,
      singleClasses: "picker_4",
      locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  </script>
@endpush

@section('css')
  <!-- bootstrap-daterangepicker -->
  <link href="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection

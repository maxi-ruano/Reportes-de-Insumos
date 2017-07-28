@extends('layouts.templeate')
@section('titlePage', 'Justificacion')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ isset($justificado) ? "Editar" : "Crear" }} Justificacion</h2>
          @include('includes.headerContainer')
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

          <div class="ln_solid"></div>
            {{ Form::open(['route' => 'justificaciones.store', 'method' => 'POST', 'role' => 'form', 'files' => false, 'class' => 'form-horizontal']) }}
                <input type="hidden" name="id" value="{{ $justificado->id }}">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Narrativa <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea name="descripcion" value="{{ isset($justificado->justificacion) ? $justificado->justificacion : null }}" class="form-control" rows="3" placeholder="Narrativa" required="required"></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">Guardar Justificaion</button>
                  </div>
                </div>
              {{ Form::close() }}
            <div class="ln_solid"></div>

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

@extends('layouts.templeate')
@section('titlePage', 'Disposicion')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ isset($disposicion) ? "Editar" : "Crear" }} Disposicion</h2>
          @include('includes.headerContainer')
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        @include('includes.formBusquedaTramite')
          @if(isset($datosPersonales) && !empty($datosPersonales))
          <div class="ln_solid"></div>
            {{ Form::open(['route' => 'disposiciones.store', 'method' => 'POST', 'role' => 'form', 'files' => false, 'class' => 'form-horizontal']) }}
              <input type="hidden" name="tramite_id" value="{{ empty($tramite) ? null : $tramite->tramite_id  }}">
              <input type="hidden" name="sys_user_id_otorgante" value="{{ session('usuario_id')  }}">
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Nombre
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <h4 class="brief"><strong>{{ $datosPersonales->nombre.' '.$datosPersonales->apellido }}</strong></h4>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                          Doc
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <h4 class="brief">{{ $datosPersonales->nro_doc  }}<i></i></h4>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Narrativa <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <!-- <input type="text" id="last-name" name="last-name" required="required" class="form-control col-md-7 col-xs-12">-->
                          <textarea id="descripcion" name="descripcion" value="{{ isset($disposicion) ? $disposicion->descripcion : null }}" class="form-control" rows="3" placeholder="Narrativa" required="required"></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">Crear Disposicion</button>
                        </div>
                      </div>


            {{ Form::close() }}
            <div class="ln_solid"></div>
          @endif
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

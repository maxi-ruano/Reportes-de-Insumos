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
        @if( isset($disposicion) )
          {{ Form::open(['route' => ['disposiciones.update', $disposicion], 'method' => 'PUT', 'role' => 'form', 'files' => false, 'class' => 'form-horizontal']) }}
        @else
          @include('includes.formBusquedaTramite')
          {{ Form::open(['route' => 'disposiciones.store', 'method' => 'POST', 'role' => 'form', 'files' => false, 'class' => 'form-horizontal']) }}
        @endif

        <input type="hidden" name="tramite_id" value="{{ empty($tramite) ? null : $tramite->tramite_id  }}">
        <input type="hidden" name="sys_user_id_otorgante" value="{{ session('usuario_id')  }}">
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Narrativa <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="descripcion" name="descripcion" value="{{ isset($disposicion) ? $disposicion->descripcion : null }}" class="form-control" rows="3" placeholder="Narrativa" required="required"></textarea>
          </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="col-md-6 col-md-offset-3">
            <button id="send" type="submit" class="btn btn-info">Otorgar Disposicion</button>
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

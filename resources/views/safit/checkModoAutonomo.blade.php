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
        <div class="form-horizontal form-label-left">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Numero Documento<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="nro_doc" type="text" class="form-control" name="nro_doc" aria-describedby="NumeroDeDocumento" placeholder="Ejem ... 54468798">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nacionalidad<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('nacionalidad', $paises,   null, ['id'=>'nacionalidad', 'data-type'=>'text', 'class'=>'form-control  paises', 'tabindex'=>'-1', 'data-placeholder'=>'Seleccionar Cliente', 'required']) !!}

              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo Documento<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('tipo_doc', $tdoc,   null, ['id'=>'tipo_doc', 'data-type'=>'text', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'data-placeholder'=>'Seleccionar Cliente', 'required']) !!}
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sexo<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::select('sexo', $sexo,   null, ['id'=>'sexo', 'data-type'=>'text', 'class'=>'select2_single form-control', 'data-placeholder'=>'Seleccionar Cliente', 'required']) !!}
              </div>
            </div>
            </fieldset>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button id="preCheckButton" type="submit" class="btn btn-primary btn-block">Buscar Boleta Pago</button>
              </div>
            </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
@include('safit.resultCheckModoAutonomo')
<!-- /page content -->
@endsection

@push('scripts')
  <script src="{{ asset('vendors/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <script>
    function mostrarPreCheck(res){
        $('#logPreCheck').empty()
        for (var i = 0; i < res.length; i++) {
          crearMensajePrecheck(res[i])
        }
    }

    function crearMensajePrecheck(msj){
      type = 'danger'
      fecha_error = ''
      if(msj.validado){
        error = 'Verificado'
        type = 'success'
      }else{
        error = ((msj.error) ? msj.error.description : '')
        fecha_error = ((msj.error) ? msj.error.created_at : '')
      }
      html = '<li>'+
        '<div class="block">'+
          '<div class="tags">'+
            '<a class="btn btn-'+type+' btn-xs btn-block">'+
              '<span>'+msj.description+'</span>'+
            '</a>'+
          '</div>'+
          '<div class="block_content">'+
            '<h2 class="title">'+
                '<a>'+ error +'</a>'+
            '</h2>'+
            '<div class="byline">'+
              '<span>'+fecha_error+'</span>'+
            '</div>'+

          '</div>'+
        '</div>'+
      '</li>';
      $('#logPreCheck').append(html)
    }

    function mostrarDatosPersona(datosPersona){
      $('#nombre_texto').html(datosPersona.nombre+' '+datosPersona.apellido);
      $('#documento_texto').html(datosPersona.nro_doc);
      $('#fecha_nacimiento_texto').html(datosPersona.fecha_nacimiento);
      $('#nacionalidad_texto').html(datosPersona.nacionalidad);
    }

    function getPreCheck(nacionalidad, nro_doc, tipo_doc, sexo){
      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "GET",
          url: '{{ config('app.url') }}'+'/deve_teorico/public/consultarPreCheck',
          data: {
                 nacionalidad: nacionalidad,
                 nro_doc: nro_doc,
                 tipo_doc: tipo_doc,
                 sexo: sexo
               },
          //async:false,
          beforeSend: function(){

          },
          success: function( msg ) {
            console.log(msg);
            if(msg){
                mostrarPreCheck(msg.precheck)
                mostrarDatosPersona(msg.datosPersona)
            }else
              mostrarMensajeError()
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            console.log(err)

          }
      });
    }

    function mostrarMensajeError(){
      $('#logPreCheck').append('<li><label class="btn btn-danger">El tramite no a sido iniciado por el precheck</label></li>')
    }

    function validaciones(){
      return true
    }

    function limpiarCampos(){
      $('#nombre_texto').html("");
      $('#documento_texto').html("");
      $('#fecha_nacimiento_texto').html("");
      $('#nacionalidad_texto').html("");
      $('#logPreCheck').html("");
    }

    $('#preCheckButton').on('click', function (e) {
      if(validaciones()){
        e.preventDefault();
        limpiarCampos()
        getPreCheck(
          $('#nacionalidad').val(),
          $('#nro_doc').val(),
          $('#tipo_doc').val(),
          $('#sexo').val()
        );
       }
    });
  </script>

  <script src="{{ asset('vendors/validator/validator.js')}}"></script>
@endpush

@section('css')

@endsection

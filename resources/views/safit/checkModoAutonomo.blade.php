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
                <input id="nro_doc" type="text" class="form-control" name="nro_doc" maxlength="10" aria-describedby="NumeroDeDocumento" placeholder="Ejem ... 54468798">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button id="buscarTramite" type="submit" class="btn btn-primary btn-block">Buscar</button>
              </div>
            </div>
            <table id="tramites" class="table table-striped">
              <thead>
                <tr>
                  <th>Nro Documento</th>
                  <th>Tipo Documento</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Nacionalidad</th>
                  <th>Fecha Turno</th>
                  <th>Hora Turno</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

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
        fecha_error = (msj.comprobante) ? 'Comprobante Nro. '+msj.comprobante : '';
      }else{
        var prop = 'description'
        if (msj.error){
          if(msj.error.description)
            error =  msj.error.description
        }else{
          error =  'No verificado'
        }

        //Si tiene un Plan de Pagos mostrar su fecha de vencimiento
        if(error.toUpperCase().indexOf("PLAN DE PAGO") > -1){
          var metadata = JSON.parse(msj.error.response_ws);
          var data = metadata.filter(metadataObj => metadataObj.tag.indexOf("AUTORIZACION") > -1);
          var fecha_vencimiento = JSON.stringify(data[0]['attributes']['FECHAVTOLICENCIA']);
          fecha_error = '<span class="red"> Plan de Pago con Fecha Vencimiento: '+fecha_vencimiento+'</span>';
          type = 'warning';

        }else{
          fecha_error = ((msj.error) ? msj.error.created_at : '')
        }

      }
      html = '<li>'+
        '<div class="block_precheck">'+
          '<div class="tags_precheck">'+
            '<a id="buttonValidacion" class="btn btn-'+type+' btn-xs btn-block">'+
              '<span>'+msj.description+'</span>'+
            '</a>'+
          '</div>'+
          '<div class="block_content">'+
            '<h2 class="title">'+
                '<a id="textoValidacion">'+ error +'</a>'+
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
      //Convertir fecha a dd-mm-yyyy
      var f = datosPersona.fecha_nacimiento.split('-');
      var fecha_nac = f[2] +"-"+ f[1]+"-"+f[0];

      $('#nombre_texto').html(datosPersona.nombre+' '+datosPersona.apellido);
      $('#documento_texto').html(datosPersona.nro_doc);
      $('#fecha_nacimiento_texto').html(fecha_nac);
      $('#nacionalidad_texto').html(datosPersona.nacionalidad);

      if (datosPersona.fecha_paseturno == null)
        $('#logTurno').html(' <a id="btnFechaPaseTruno" onclick="getPaseTurno('+datosPersona.id+')" class="btn btn-danger btn-block"><span>SIGUIENTE SECTOR</span> <i class="fa fa-sign-in"></i></a> ');
      else
        $('#logTurno').html(' <a id="btnFechaPaseTruno" class="btn btn-success btn-block"><i class="fa fa-check-circle"></i> <span>PASO AL SIGUIENTE SECTOR <b>'+datosPersona.fecha_paseturno+'</b> </span> </a> ');
  
    }

    function getPaseTurno(id){
      $.ajax({
          type: "POST",
          url: '/api/funciones/actualizarPaseATurno',
          data: { id: id},
          success: function( msg ) {
            getPreCheck(id);
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
          }
      });
    }


    function getPreCheck(id){
      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "GET",
          url: '/consultarPreCheck',
          data: { id: id, },
          //async:false,
          success: function( msg ) {
            if(msg.error){
              mostrarMensajeError(msg.error)
            }else if(msg){
              mostrarPreCheck(msg.precheck)
              mostrarDatosPersona(msg.datosPersona)
            }
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
          }
      });
    }

    function mostrarMensajeError(error){
      $('#logPreCheck').append('<li><label class="btn btn-danger">'+error+'</label></li>')
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

    function actualizarValidacion(type, mensaje){
      $('#textoValidacion').html(mensaje);
      $('#buttonValidacion').attr('class', 'btn btn-'+type+' btn-xs btn-block')
    }

    function cargarListaTramites(tramites){

      tramites.forEach(e => {
        var f = e.fecha.split('-');
        var fecha = f[2] +"/"+ f[1]+"/"+f[0];
        $('#tramites tbody').append('<tr>'+
                  '<th scope="row">'+e.nro_doc+'</th>'+
                  '<td>'+e.tipo_doc+'</td>'+
                  '<td>'+e.nombre+'</td>'+
                  '<td>'+e.apellido+'</td>'+
                  '<td>'+e.nacionalidad+'</td>'+
                  '<td>'+fecha+'</td>'+
                  '<td>'+e.hora+'</td>'+
                  '<td><button type="button" onclick="getPreCheck('+e.id+')" class="btn btn-primary btn-sm">Seleccionar</button></td>'+
                '</tr>');
      });
    }

    $('#buscarTramite').on('click', function (e) {
      $('#tramites tbody').empty();
      limpiarCampos();
      $('#logTurno').empty();

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "GET",
          url: '/buscarTramitesPrecheck',
          data: { nro_doc: $('#nro_doc').val(), },
          success: function( msg ) {
            if(msg.error)
              mostrarMensajeError(msg.error)
            else
              cargarListaTramites(msg.res) 
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
         }
      });  
    });

    $(document).on("keypress", "input", function(event){
      var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13'){
        $('#buscarTramite').click();
      }
    });
    
  </script>

  <script src="{{ asset('vendors/validator/validator.js')}}"></script>
@endpush

@section('css')
<link href="{{ asset('css/precheck.css') }}" rel="stylesheet">
@endsection

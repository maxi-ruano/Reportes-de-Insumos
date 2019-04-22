@extends('layouts.templeate')
@section('content')
<!-- page content -->

@include('safit.botoneraPrecheck')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <h2>Revisar Pre-Check</h2>
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
                  <th>Sede</th>
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
                  '<td>'+e.sucursal+'</td>'+
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
          url: 'buscarTramitesPrecheck',
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

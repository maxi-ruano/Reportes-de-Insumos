
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Datos Tramite <small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
          <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <h3 class="text-center name"><div id="nombre_texto"></div></h3>
        <div class="flex">
          <ul class="list-inline count2">
            <li>
              <h3><div id="documento_texto"></div></h3>
              <span>Documento</span>
            </li>
            <li>
              <h3><div id="fecha_nacimiento_texto"></div></h3>
              <span>Fecha de Nacimiento</span>
            </li>
            <li>
              <h3><div id="nacionalidad_texto"></div></h3>
              <span>Nacionalidad</span>
            </li>
          </ul>
        </div>
        <div id="logTurno">
          
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Log de Pre-Check <small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div id="tramite" class="x_content">
          {!! Form::hidden('tramite_a_iniciar_id', '', ['id' => 'tramite_a_iniciar_id', 'class' => 'form-control']) !!}        
          <ul class="list-unstyled timeline">
            <div id="logPreCheck"></div>
          </ul>
      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    
    /* TODO este codigo debera ir en un js compilado, ya q es reutilizado en checkModoAutonomo.blade.php*/
    function getPreCheck(id){
      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "GET",
          url: '/consultarPreCheck',
          data: { id: id, },
          async:false,
          success: function( msg ) {
            if(msg.error){
              mostrarMensajeError(msg.error)
            }else if(msg){
              mostrarPreCheck(msg.precheck)
              mostrarDatosPersona(msg.datosPersona)

              //Bloquear todas las opciones del PreCheck para el Rol Auditoria
              @if(Auth::check())
                @if(Auth::user()->hasRole('Auditoria'))
                  $(".modal-body a").attr("disabled","disabled").attr('onclick','');
                @endif
              @endif
              
            }
          },
          error: function(xhr, status, error) {
              var err = eval("(" + xhr.responseText + ")");
          }
      });
  }

  function mostrarPreCheck(res){
      $('#logPreCheck').empty();
      for (var i = 0; i < res.length; i++) {
      crearMensajePrecheck(res[i])
      }
  }

  function mostrarDatosPersona(datosPersona){

      console.log(datosPersona);

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

  function mostrarMensajeError(error){
     $('#logPreCheck').html('<li><label class="btn btn-danger">'+error+'</label></li>')
  }

  function crearMensajePrecheck(msj){
      console.log(msj);

      $("#tramite_a_iniciar_id").val(msj.tramite_a_iniciar_id);

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

      //Colocar el metodo onclick solo si no se ha verificado (type=danger)
      var precheckOnclick = '';
      if(type=='danger')
          precheckOnclick = 'onclick="runPrecheck('+msj.tramite_a_iniciar_id+','+msj.validation_id+')" ';

      //Boton del Log Prec-Check con su descripcion y fecha de ejecucion o Nro. Comrpobante
      html = '<li>'+
          '<div class="block_precheck">'+
          '<div class="tags_precheck">'+
              '<a id="buttonValidacion" '+precheckOnclick+' class="btn btn-'+type+' btn-xs btn-block">'+
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

      //Emitir Certificado SAFIT si no se encontro por WS
      if(msj.validation_id == '3' && type=='danger' && error != 'No verificado'){
        var options;
        @foreach ($centrosEmisores as $key => $value)
              options += '<option value="{{ $value->safit_cem_id }}"> {{ $value->name }} </option>';
        @endforeach

        html+='<h4> <i class="fa fa-chevron-circle-right"></i> Generar CENAT <span class="msjcenat red"></span> </h4>';
        html+='<div class="col-md-5 col-sm-5 col-xs-12">'+
              '<input type="number" class="form-control" id="bop_cb" name="bop_cb" aria-describedby="codigoPagoElectronico" placeholder="Código Pago Electrónico">'+
              '</div>';
        html+='<div class="col-md-5 col-sm-5 col-xs-12">'+
              '<select id="cem_id" name="cem_id" class="select2_single form-control" data-placeholder="Seleccionar Centro Emisor">'+options+'</select>'+
              '</div>';

        html+='<div class="col-md-2 col-sm-2 col-xs-12"> <button type="button" onclick="generarCenat()" class="btn btn-primary btn-block" title="Generar Certificado Virtual"> <i class="fa fa-cloud-download fa-lg"></i> </div>';
      }

      $('#logPreCheck').append(html)
  }

  function generarCenat(){
    var id = $("#tramite_a_iniciar_id").val();
    var bop_cb = $("#bop_cb").val();
    var cem_id = $("#cem_id").val();

    if(bop_cb == ''){
      $('#logPreCheck .msjcenat').html('*** ingrese el código del Pago Electronico');
    }else{
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: '/generarCenatPrecheck',
        data: {id: id, bop_cb: bop_cb, cem_id: cem_id },
        beforeSend: function(){
          $('#logPreCheck').html('<img src="/img/buffer.gif" width="200" > Generando CENAT... espere.');
        },
        success: function( msg ) {          
            getPreCheck(id);
            $('#logPreCheck .msjcenat').html('***'+msg);
        },
        error: function(xhr, status, error) {
          $('#logPreCheck').html('ocurrio un error!! Intenta de nuevo...');
        }
      });
    }
  }

  function runPrecheck(id, validation){
    console.log(id+' '+validation);
    
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: "GET",
      url: '/runPrecheck',
      data: { id: id, validation: validation },
      Async:true,
      beforeSend: function(){
        $('#logPreCheck').html('<img src="/img/buffer.gif" width="200" > Verificando... ');
      },
      success: function( msg ) {
        console.log('Finalizo: '+msg);
        getPreCheck(id);
      },
      error: function(xhr, status, error) {
        $('#logPreCheck').html('ocurrio un error!! Intenta de nuevo...');
      }
    });

    console.log('continuando');
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

  </script>
@endpush
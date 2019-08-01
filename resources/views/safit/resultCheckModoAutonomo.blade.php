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
      $("#nombre_texto, #documento_texto, #fecha_nacimiento_texto, #nacionalidad_texto").empty();

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "GET",
          url: 'consultarPreCheck',
          data: { id: id },
          async:false,
          success: function( msg ) {
            if(msg.error){
              mostrarMensajeError(msg.error, tramite_habilitado_id);
            }else if(msg){

              mostrarPreCheck(msg.precheck, msg.datosPersona);

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

  function mostrarPreCheck(precheck, tramite){
      
      mostrarDatosPersona(tramite);  
      $('#logPreCheck, #logTurno').empty();
      
      for (var i = 0; i < precheck.length; i++) {
        crearMensajePrecheck(precheck[i], tramite);
      }
      
  }

  function mostrarDatosPersona(datosPersona){

      //Convertir fecha a dd-mm-yyyy
      var f = datosPersona.fecha_nacimiento.split('-');
      var fecha_nac = f[2] +"-"+ f[1]+"-"+f[0];

      $('#nombre_texto').html(datosPersona.nombre+' '+datosPersona.apellido);
      $('#documento_texto').html(datosPersona.nro_doc);
      $('#fecha_nacimiento_texto').html(fecha_nac);
      $('#nacionalidad_texto').html(datosPersona.nacionalidad);
  }

  function mostrarMensajeError(error, tramite_habilitado_id){
    $('#logPreCheck').html('<li><label class="btn btn-danger">'+error+'</label></li>')
  }

  function crearMensajePrecheck(precheck, tramite){
    $("#tramite_a_iniciar_id").val(precheck.tramite_a_iniciar_id);
    var type = 'danger';
    var info = '';
    var verificado = false;
    var precheck_libredeuda = false;

    var date = new Date().toISOString().slice(0,10); ;
    var updated = new Date(precheck.updated_at).toISOString().slice(0,10);
  
      if(precheck.validado){
          error = 'Verificado';
          type = 'success';
          verificado = true;

          if(precheck.description == 'LIBRE DEUDA'){
            precheck_libredeuda = true;
          }

          if(precheck.description == 'BUI'){
            info = (precheck.boleta) ? ' Boleta Nro. <span class="red">' + precheck.boleta.nro_boleta + '</span> Importe <span class="red"> $ ' + precheck.boleta.importe_total + '</span> Fecha de pago <span class="green"> ' + precheck.boleta.fecha_pago + '</span>' : '';
          }else{
            info = (precheck.comprobante) ? 'Comprobante Nro. <span class="red">'+precheck.comprobante+' </span> <br>'+precheck.updated_at : '';
          }

      }else{
        var prop = 'description';
        if (precheck.error){
          if(precheck.error.description){
            error =  precheck.error.description
            verificado = true;
          }
        }else{
          error =  'No verificado';
        }

        //Si tiene un Plan de Pagos mostrar su fecha de vencimiento
        if(error.toUpperCase().indexOf("PLAN DE PAGO") > -1){
          var metadata = JSON.parse(precheck.error.response_ws);
          var data = metadata.filter(metadataObj => metadataObj.tag.indexOf("AUTORIZACION") > -1);
          var fecha_vencimiento = JSON.stringify(data[0]['attributes']['FECHAVTOLICENCIA']);
          info = '<span class="red"> Plan de Pago con Fecha Vencimiento: '+fecha_vencimiento+'</span>';
          type = 'warning';
          verificado = true;
          precheck_libredeuda = true;

        }else{
          info = ((precheck.error) ? precheck.error.created_at : '')
        }
      }

      //Agregar onclick solo si no ha iniciado en LICTA, no corresponda a Sinalic
      var precheckOnclick = '';
      if(tramite.tramite_dgevyl_id == null && precheck.description != 'SINALIC' && type != 'success' ){
          precheckOnclick = 'onclick="runPrecheck('+precheck.tramite_a_iniciar_id+','+precheck.validation_id+')" ';
      }

      //Boton del Log Prec-Check con su descripcion y fecha de ejecucion o Nro. Comrpobante
      html = '<li>'+
          '<div class="block_precheck">'+
          '<div class="tags_precheck">'+
              '<a id="btn_precheck_'+precheck.validation_id+'" '+precheckOnclick+' class="btn btn-'+type+' btn-xs btn-block">'+
              '<span>'+precheck.description+'</span>'+
              '</a>'+
          '</div>'+
          '<div class="block_content">'+
              '<h2 class="title">'+
                  '<a id="textoValidacion">'+ error +'</a>'+
              '</h2>'+
              '<div class="byline">'+
              '<span>'+info+'</span>'+
              '</div>'+

          '</div>'+
          '</div>'+
      '</li>';

      //Emitir Certificado SAFIT si no se encontro por WS
      if(precheck.validation_id == '3' && type=='danger' && error != 'No verificado'){
        var options;
        @foreach ($centrosEmisores as $key => $value)
          @if($value->safit_cem_id == 1)
            options += '<option value="{{ $value->safit_cem_id }}" selected="selected"> {{ $value->name }} </option>';
          @else
            options += '<option value="{{ $value->safit_cem_id }}"> {{ $value->name }} </option>';
          @endif
        @endforeach

        html+='<h4> <i class="fa fa-chevron-circle-right"></i> Generar CENAT <span class="msjcenat red"></span> </h4>';
        html+='<div class="col-md-5 col-sm-5 col-xs-12">'+
              '<input type="number" class="form-control" id="bop_cb" name="bop_cb" aria-describedby="codigoPagoElectronico" placeholder="ID Boleta">'+
              '</div>';
        html+='<div class="col-md-5 col-sm-5 col-xs-12">'+
              '<select id="cem_id" name="cem_id" class="select2_single form-control" data-placeholder="Seleccionar Centro Emisor">'+options+'</select>'+
              '</div>';

        html+='<div class="col-md-2 col-sm-2 col-xs-12"> <button type="button" onclick="generarCenat()" class="btn btn-primary btn-block" title="Generar Certificado Virtual"> <i class="fa fa-cloud-download fa-lg"></i> </div>';
      }

      $('#logPreCheck').append(html);
      
      if (tramite.fecha_paseturno != null){
        $('#logTurno').html(' <a class="btn btn-success btn-block"><i class="fa fa-check-circle"></i> <span>PASO AL SIGUIENTE SECTOR <b>'+tramite.fecha_paseturno+'</b> </span> </a> ');
      }else{
        //RESTRINGIR PASE SIGUIENTE SECTOR
        if(precheck.description != 'SINALIC'){
          if(verificado){
            if(precheck.description == 'LIBRE DEUDA'){
              if (precheck_libredeuda == true){
                  $('#logTurno').html(' <a onclick="getPaseTurno('+tramite.id+')" class="btn btn-danger btn-block"><span>SIGUIENTE SECTOR</span> <i class="fa fa-sign-in"></i></a> ');
              }else{
                $('#logTurno').append("(*) <span class='red'> Debe verificar "+precheck.description+" </span> <br>");
              }
            }
          }else{
            $('#logTurno').append("(*) <span class='red'> Debe verificar "+precheck.description+" </span> <br>");
            $("#btn_precheck_"+precheck.validation_id).click();
          }  
        }
      }

      return verificado;
  }

  function generarCenat(){
    var id = $("#tramite_a_iniciar_id").val();
    var bop_cb = $("#bop_cb").val();
    var cem_id = $("#cem_id").val();

    if(bop_cb == ''){
      $('#logPreCheck .msjcenat').html('*** ingrese el ID de la Boleta');
    }else{
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: 'generarCenatPrecheck',
        data: {id: id, bop_cb: bop_cb, cem_id: cem_id },
        beforeSend: function(){
          var img = "{{  URL::to('/') }}/img/buffer.gif";
          $('#logPreCheck').html('<img src="'+img+'" width="200" > Generando CENAT... espere.');
        },
        success: function( msg ) {          
            getPreCheck(id);
            $('#logPreCheck .msjcenat').html('***'+msg[1]);
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
      url: 'runPrecheck',
      data: { id: id, validation: validation },
      Async:true,
      beforeSend: function(){
        var img = "{{  URL::to('/') }}/img/buffer.gif";
        $('#logPreCheck').html('<img src="'+img+'" width="200" > Verificando... ');
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
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: 'actualizarPaseATurno',
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

function actualizarMonitor(){

  console.log( 'Y' );
  $.ajax({
      type: "GET",
      url: url,
      //data: {examen_id: examen_id, respuesta_id: respuesta_id, pregunta_id: pregunta_id},
      //async:false,
      beforeSend: function(){

      },
      success: function( msg ) {

        renderMonitorComputadoras(msg)
      },

      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
      }
  });
}

function renderMonitorComputadoras(msg){
  $('.computadoras').empty();
  for(var i = 0; i < msg['computadoras'].length; i++){
    console.log('for '+i)
    $('.computadoras').append(gethtmlComputadora(msg['computadoras'][i]))
  }
}

function gethtmlComputadora(computadora){
  console.log(computadora)
  var estadoComputadora = '';
  if(computadora.activo)
    estadoComputadora = 'fondo-pc-ocupado';
  return '<div class="col-md-55">'+
      '<div class="thumbnail '+estadoComputadora+'" style = "height: auto;">'+
        '<div class="row">'+
          '<div class="col-md-6">'+
              '<img  class="img-pregunta img-responsive" onerror="this.src=\''+imagenDefault+'\'"  style = "height: 120px; width: auto;" src="'+computadora.pathFoto+'" alt="Generic placeholder thumbnail">'+
          '</div>'+
          '<div class="col-md-6" style="color: #333; height: 40%; padding: 1px;">'+
            '<ul class=" pcs-monitor list-unstyled">'+
              '<li><b>PC: '+computadora.id+'</b></li>'+
              '<li>Doc: '+computadora.nro_doc+'</li>'+
              '<li>'+computadora.nombre+'</li>'+
              '<li>'+computadora.apellido+'</li>'+
              '<li><b>'+computadora.estadoExamen+'</span></b></li>'+
            '</ul>'+
          '</div>'+
        '</div>'+
      '</div>'+
    '</div>';
}

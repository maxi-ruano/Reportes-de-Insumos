
function verificarAsignacion(){
  $.ajax({
      type: "GET",
      url: url_reload,
      //async:false,
      beforeSend: function(){

      },
      success: function( msg ) {
        verificaReload(msg)
      },

      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
      }
  });
}

function verificaReload(msg){
  if(msg.length != 0)
    if(msg.res == 'true')
      location.href = url_reload_examen;
}

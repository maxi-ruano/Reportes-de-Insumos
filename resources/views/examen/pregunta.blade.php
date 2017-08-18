@extends('layouts.templeateExamen')

@section('pregunta')
  <div class="row">
      <div class="col-sm-8 div-pregunta" >
        <textarea class="form-control textoPregunta" rows="5" style=" resize: none; border: none; font-size: 25px; white-space: normal; background-color: #fff;" disabled></textarea>
      </div>
      <div class="col-sm-4 div-pregunta-img">
        <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Popup image</button>-->
      </div>
      <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
              <div class="modal-body">
                <div class="text-center">
                  <img src="" class="img-pregunta img-responsive" style = "height: 50%;width: auto;">
                </div>
              </div>
          </div>
        </div>
      </div>
  </div>
@endsection

@section('persona')
  <h4>{!! $nombre !!}</h4>
  <img src="{!! $fotografia !!}" onerror="this.src='{{ asset('production/images/user.png')}}'" alt="..." class=" img-thumbnail img-responsive img-persona" style = "height: 70%;width: auto;">
@endsection

@section('respuestas')
  <fieldset class="form-group">
    <legend>Selecciones su respuesta:</legend>
    <div class="row">
      <div class="col-sm-12 option-respuestas btn-group-vertical" data-toggle="buttons">
    </div>
  </div >
  </fieldset>
@endsection

<script>
  var preguntas = new Array();
</script>

@section('scripts')
dd( count($preguntas));
  <script type="text/javascript">
    var examen = '{!! $examen !!}';
    var pregunta;
    var idSiguiente = 0; var errorConexion = 0;
    var cantidadPreguntas = '{{ count($preguntas) }}';
    function cargarPregunta(){
      $('.textoPregunta').html(Base64.decode(preguntas[idSiguiente]['pregunta']));
      if(preguntas[idSiguiente]['imagen']){
        $(".div-pregunta").removeClass('col-sm-12').addClass('col-sm-8');
        $(".div-pregunta-img").html('<div class="thumbnail text-center"><img src="" alt="..." class="img-pregunta img-responsive" data-toggle="modal" data-target="#myModal" style = "height: 21.5%;width: auto;">'+'<div class="profile_info">hacer click para agrandar la foto</div></div>')
        $(".img-pregunta").attr('src', '{{ config('global.IMAGENES_PREGUNTAS') }}' + preguntas[idSiguiente]['imagen']);
      }else{
        $(".div-pregunta-img").empty();
        $(".div-pregunta").removeClass('col-sm-8').addClass('col-sm-12');
      }

      $('').html('<h2>'+preguntas[idSiguiente]['imagen']+'</h2>');
      pregunta = preguntas[idSiguiente]['id'];
      var respuestas = preguntas[idSiguiente]['respuestas'];

      $('.option-respuestas').empty();

      for (var i = 0; i < respuestas.length; i++) {
        $('.option-respuestas').append(
          '<label class="botonRespuesta btn btn-primary btn-responsive" style="white-space: normal;">'+
            '<input type="radio" class="form-check-input" name="optionsRadios" value="'+respuestas[i]['id']+'">'+
            '<h3 align="left">'+'{{ config('global.LETRAS') }}'.charAt(i)+'. '+Base64.decode(respuestas[i]['respuesta'])+'</h3>'+
          '</label><br>'
        );
      }

      idSiguiente = idSiguiente+1;
      $('.progress-preguntas').css('width', ((100/preguntas.length)*idSiguiente)+'%');
      $('.numerador-preguntas').text('Pregunta '+idSiguiente + ' de ' + preguntas.length)

    }

    function enviarRespuestas(){
        $('.preguntaDiv').text('se envio');
    }

    function validaciones(){
      var res = false;

      if($('input[name=optionsRadios]:checked').val() != null)
        res = true;
      else
        alert('Debe seleccionar una respuesta');

      return res;
    }

    function finalizarExamenLimiteSuperado(){
      // se envia examen cuado se acaba el tiempo
      $('.examen_input').attr('value', examen);
      document.getElementById("finalizar_examen").submit();
      // end se envia examen cuado se acaba el tiempo
    }
    if(cantidadPreguntas > 0)
        cargarPregunta();
      else {
        if (confirm('Todas las preguntas de este examen ya han sido respondidas, si es asi, seleccione "OK" para finalizar el examen. Si no es el caso por favor comuniquese con el encargado del aula, disculpe las molestias.')) {
          $('.examen_input').attr('value', examen);
          document.getElementById("finalizar_examen").submit();
        }
      }

    //GUARDAR RESPUESTAS AJAX
    function enviarRespuesta(){
      var examen_id = examen;
      var pregunta_id = pregunta;
      var respuesta_id = $('input[name=optionsRadios]:checked').val();

      $.ajax({
          type: "GET",
          url: '{{ config('app.url') }}'+'{{ config('global.GUARDAR_RESPUESTA_EXAMEN') }}',
          data: {examen_id: examen_id, respuesta_id: respuesta_id, pregunta_id: pregunta_id},
          //async:false,
          tryCount : 0,
          retryLimit : 3,
          beforeSend: function(){
            $('#botonPregunta').attr('disabled','disabled');
            $('.botonRespuesta').attr('disabled','disabled');
          },
          success: function( msg ) {
            if(msg.res == 'success'){
              if(idSiguiente != cantidadPreguntas){
                $('#botonPregunta').prop('disabled',false);
                $('.botonRespuesta').attr('disabled','disabled');
                cargarPregunta();
                if(cantidadPreguntas == idSiguiente)
                  $('#botonPregunta').text('Finalizar Examen');
              }else{
                $('.examen_input').attr('value', examen);
                document.getElementById("finalizar_examen").submit();
              }
            }
            if(errorConexion){
              Example2.Timer.toggle();
              errorConexion = 0;
            }

          },
          error: function(xhr, status, error) {
            errorConexion = 1;
            Example2.resetCountdown();
            if (status == 'timeout' || xhr.readyState == 0) {
              $.notify('Intentando reconectar con el servidor ...', {style: 'reconectando'});
              setTimeout(function()
              {
                enviarRespuesta();
              }, 10000);
               var err = eval("(" + xhr.responseText + ")");
              }
            }

      });
    }
      $('#botonPregunta').on('click', function (e) {
        if(validaciones()){
          e.preventDefault();
          enviarRespuesta()
         }
        });
      // MOSTRAR IMAGEN PREGUNTA EN MODAL
      function centerModal() {
        $(this).css('display', 'block');
        var $dialog = $(this).find(".modal-dialog");
        var offset = ($(window).height() - $dialog.height()) / 2;
        $dialog.css("margin-top", offset);
      }

      $('.modal').on('show.bs.modal', centerModal);
      $(window).on("resize", function () {
        $('.modal:visible').each(centerModal);
      });
      //SE DESHABILITA EL LA LLAMADA ATRAS Y EL CLICK DERECHO
      $(document).ready(function() {
           function disableBack() { window.history.forward() }
           window.onload = disableBack();
           window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
       });
       document.oncontextmenu = function(){return false;}
  </script>
@endsection


@foreach($preguntas as $pregunta)
  <script>
    var respuestas = new Array();
  </script>
  @foreach($pregunta->respuestas as $respuesta)
    <script>
      respuestas.push({respuesta:'{!! base64_encode($respuesta->EtlRespuesta->texto) !!}',
                       id:'{!! $respuesta->EtlRespuesta->etl_respuesta_id !!}'});
    </script>
  @endforeach
    <script>
      var pregunta = {pregunta:'{!! base64_encode($pregunta->etlPregunta->texto) !!}',
                      id:'{!! $pregunta->etlPregunta->etl_pregunta_id !!}',
                      imagen:'{!! $pregunta->etlPregunta->imagen !!}',
                      respuestas:respuestas};
      preguntas.push(pregunta);
    </script>
@endforeach

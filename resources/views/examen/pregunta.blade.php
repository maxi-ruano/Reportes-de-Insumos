@extends('layouts.templeateExamen')

@section('pregunta')
  <row>
      <div class="col-sm-8">
        <p class="textoPregunta"></p>
      </div>
      <div class="col-sm-4 div-pregunta-img">


        <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Popup image</button>-->


      </div>
      <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body">
                  <img src="" class="img-pregunta img-responsive">
              </div>
          </div>
        </div>
      </div>
  </row>


@endsection
@section('nombre')
  Diego Torres
@endsection

@section('respuestas')
  <fieldset class="form-group">
    <legend>Selecciones su respuesta:</legend>
    <div class="option-respuestas">
    </div>
  </fieldset>
@endsection

<script>
  var preguntas = new Array();

</script>

@section('scripts')
  <script>
    var examen = '{!! $examen !!}';
    var pregunta;
    var idSiguiente = 0;
    function cargarPregunta(){

      $('.textoPregunta').html('<h2>'+preguntas[idSiguiente]['pregunta']+'</h2>');
      if(preguntas[idSiguiente]['imagen']){
        $(".div-pregunta-img").html('<img src="" alt="..." class="img-pregunta img-thumbnail img-responsive" data-toggle="modal" data-target="#myModal">')
        $(".img-pregunta").attr('src','http://192.168.76.200/etlnuevo/assets/images/' + preguntas[idSiguiente]['imagen']);
      }else
        $(".div-pregunta-img").empty();

      $('').html('<h2>'+preguntas[idSiguiente]['imagen']+'</h2>');
      pregunta = preguntas[idSiguiente]['id'];
      var respuestas = preguntas[idSiguiente]['respuestas'];

      $('.option-respuestas').empty();

      for (var i = 0; i < respuestas.length; i++) {
        $('.option-respuestas').append('<div class="form-check">'+
          '<label class="form-check-label">'+
            '<h2>'+'<input type="radio" class="form-check-input" name="optionsRadios" value="'+respuestas[i]['id']+'" checked>'+
            '&nbsp&nbsp'+respuestas[i]['respuesta']+'</h2>'+
          '</label>'+
        '</div>');
      }

      idSiguiente = idSiguiente+1;
      $('.progress-preguntas').css('width', ((100/preguntas.length)*idSiguiente)+'%');
      $('.numerador-preguntas').text('Pregunta '+idSiguiente + ' de ' + preguntas.length)

      actualizarBoton();
    }

    function actualizarBoton(){
      if(idSiguiente == 30){
        $('#botonPregunta').text("Finalizar Examen");
        $('#botonPregunta').attr("onclick","enviarRespuestas()");
      }
    }

    function enviarRespuestas(){
        $('.preguntaDiv').text('se envio');
    }
    // Set the date we're counting down to
    var minutos = new Date();
    minutos.setMinutes(minutos.getMinutes() + 45);

    var countDownDate = minutos.getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        $('#regresion').html('<h1>'+hours + "h " + minutes + "m " + seconds + "s "+'</h1>');
        $('.progress-tiempo').css('width', ((1-(minutes/45))*100 )+'%');
        //document.getElementById("regresion").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            $('#regresion').html("EXPIRED");
        }
    }, 1000);
    cargarPregunta();
    //GUARDAR RESPUESTAS AJAX
      $('#botonPregunta').on('click', function (e) {

          e.preventDefault();
          var examen_id = examen;
          var pregunta_id = pregunta;
          var respuesta_id = $('input[name=optionsRadios]:checked').val();
          $.ajax({
              type: "GET",
              url: 'http://192.168.76.215/deve_teorico/public/guardar_respuesta',
              data: {examen_id: examen_id, respuesta_id: respuesta_id, pregunta_id: pregunta_id},
              //async:false,
              beforeSend: function(){
                $('#botonPregunta').attr('disabled','disabled');
              },
              success: function( msg ) {
                $('#botonPregunta').prop('disabled',false);
                cargarPregunta();
                console.log(msg)
              },

              error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
              }
          });
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
  </script>
@endsection
<script>

</script>
@foreach($preguntas as $pregunta)
  <script>
    var respuestas = new Array();
  </script>
  @foreach($pregunta->respuestas as $respuesta)
    <script>
      respuestas.push({respuesta:'{!! $respuesta->EtlRespuesta->texto !!}',
                       id:'{!! $respuesta->EtlRespuesta->etl_respuesta_id !!}'});
    </script>
  @endforeach
    <script>
      var pregunta = {pregunta:'{!! $pregunta->etlPregunta->texto !!}',
                      id:'{!! $pregunta->etlPregunta->etl_pregunta_id !!}',
                      imagen:'{!! $pregunta->etlPregunta->imagen !!}',
                      respuestas:respuestas};
      preguntas.push(pregunta);
    </script>
@endforeach

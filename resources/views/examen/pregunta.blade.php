@extends('layouts.templeateExamen')

@section('pregunta')
  <p class="textoPregunta"></p>
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
  var pregunta_id;
</script>

@section('scripts')
  <script>
    function siguientePregunta(idSiguiente){
      //console.log(res)
      console.log(preguntas);
      $('.textoPregunta').html('<h2>'+preguntas[idSiguiente]['pregunta']+'</h2>');
      pregunta_id = preguntas[idSiguiente]['pregunta_id'];
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



      console.log('idSiguiente: '+idSiguiente);
      if(idSiguiente != 30){
        $('#botonPregunta').attr("onclick","siguientePregunta("+idSiguiente+")");
      }else{
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
    siguientePregunta(0);


    //GUARDAR RESPUESTAS AJAX
    //$('#submit').on('submit', function (e) {
    $('#boton').on('click', function (e) {
        //console.log($('.optionsRadios').find(":selected").id;
        console.log(examen);

        e.preventDefault();
        var examen = examen;
        var pregunta_id = pregunta_id;
        var respuesta_id = $('input[name=optionsRadios]:checked').val();//17534
        $.ajax({
            type: "GET",
            url: 'http://localhost/deve_teorico/public/guardar_respuesta',
            data: {examen: examen, respuesta_id: respuesta_id, pregunta_id: pregunta_id},
            success: function( msg ) {
              console.log(msg)
                //$("#ajaxResponse").append("<div>"+msg+"</div>");
            },

            error: function(xhr, status, error) {
              var err = eval("(" + xhr.responseText + ")");
              console.log(err.Message);
            }

        });
    });

  </script>
@endsection
<script>
  var examen = '{!! $examen !!}';
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

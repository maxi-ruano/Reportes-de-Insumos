
@foreach($preguntas as $pregunta)
  {{ $pregunta->etlPregunta->texto }}
@endforeach

{{ Form::open(['route' => 'guardar_respuesta', 'method' => 'POST', 'role' => 'form', 'files' => false]) }}

        <p>aqui va mi pregunta</p>
        <input type="text" name="respuesta" value="">
        <input type="submit" value="enviar">

{{ Form::close() }}

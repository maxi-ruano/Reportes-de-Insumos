<?php
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | </title>

      <link href="{{ asset('page_block/styles.css')}}" rel="stylesheet">
      <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <style>

    </style>
  </head>

  <body>
    <header id="header">
    <div class="top"></div>
    <a href="#" class="logo"></a>
    </header>
    <div class="heroImage">
      <div class="links">
         <div class="cuadrado">
           @if(!empty($examen))
           <h2 style="color:#585757;"><b>Usted ha finalizado su examen teórico.</b></h2>
               @if($examen->aprobado)
                    <h4>Recuerde que su trámite tiene una validez de <b>{{ config('global.DIAS_VALIDEZ_TRAMITE') }} días</b> corridos desde el día que lo inició: <b>{{ $examen->fec_emision_modificada }} </b> y vence: <b>{{ $examen->fec_vencimiento_modificada }} </b></h4>
                    <h4>Si su trámite vence, deberá iniciarlo nuevamente; abonando todos los costos correspondientes.</h4>
                    <h4>Por favor diríjase al frente del aula para sellar su  trámite con el Bedel del aula , y luego se le indicarán los pasos a seguir.</h4>
                    <h4> Felicidades, <span class='label label-success' >APROBO</span> con un <b>{!! $examen->porcentaje !!} %.</b></h4>
                @else
                    <h4>Le quedan <b>{{ $examen->cantidadOportunidadesExamen }} ( {{ config('global.NUMEROS')[$examen->cantidadOportunidadesExamen] }}) oportunidades</b> más para volver a rendir su examen teórico</h4>
                    <h4>Deberá esperar <b>{{ config('global.DIAS_PARA_EXAMEN') }} dias</b> habiles para poder rendir nuevamente.</h4>
                    <h4>Si reprueba <b>{{ config('global.CANT_MAX_EXAM_CAT') }} veces</b> deberá realizar nuevamente el trámite desde el inicio, tenga en cuenta que el curso tiene validez de un año.</h4>
                    <h4>No podrá rendir el exámen práctico hasta finalizar exitosamente el teórico.</h4>
                    <h4>El resultado de su examen quedará registrado y asentado en todos nuestros registros y sólo podrá rendir, como se mencionó anteriormente, dentro de cinco días corridos: a partir del día <b>{{ $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') }}. </b></h4>
                    <h4>Recuerde que su trámite tiene una validez de <b>{{ config('global.DIAS_VALIDEZ_TRAMITE') }} días</b> corridos a partir del <b>{{ $examen->fec_emision_modificada }}</b> y vence: <b>{{ $examen->fec_vencimiento_modificada }}</b></h4>
                    <h4>Si su trámite vence deberá iniciarlo nuevamente abonando todos los costos correspondientes.</h4>
                    <h4>Por favor diríjase al frente del aula para ver cómo continuar su trámite con el Bedel del áula.</h4>
                    <h4>Lamentamos que haya <span class='label label-danger' >REPROBADO </span> con un <b>{!! $examen->porcentaje !!} %.</b></h4>
                @endif
            @else
                 <h1 style="color:#585757;"><b>Equipo bloqueado</b></h1>
                 <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            @endif
        </div>
      </div>
    </div>
    <footer id="footer">
    <a href="#" class="logoFooter"></a>
    </footer>

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{ asset('vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{ asset('vendors/nprogress/nprogress.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('build/js/custom.min.js')}}"></script>
    <script src="{{ asset('build/js/asignacion.js')}}"></script>
  </body>
</html>

<script type="text/javascript">

var url_reload_examen =  '{{ config('app.url') }}'+'{{ config('global.URL_EXAMEN_TEORICO') }}';
setInterval(function () {//URL_EXAMEN_TEORICO
  location.href = url_reload_examen;
}, {{ config('global.RELOAD_BLOQUEO_TEORICO') }}
);
</script>

<script type="text/javascript">
var url_reload =  '{{ config('app.url') }}'+'{{ config('global.URL_VERIFICACION_ASIGNACION') }}';
  setInterval(function () {
    verificarAsignacion();
  }, 10000
  );
  document.oncontextmenu = function(){return false;}
</script>

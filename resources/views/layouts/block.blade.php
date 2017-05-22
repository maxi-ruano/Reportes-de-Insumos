<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | </title>

    <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('build/css/custom.min.css')}}" rel="stylesheet">

    <style>
            .fondo {
          background-color: #D9DEE4;
        }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="row text-center">
              <div class="col-sm-3">
              </div>
              <div class="col-sm-6">
                <div class="contenedor-examen-panel panel panel-default ">
                  <div class="panel-body fondo">

                    @if(!empty($examen))

                    <h1><b>Usted ha finalizado su examen teórico.</b></h1>
                    <br>
                    <h2>{{ $examen->mensaje }}  <b>{{ $examen->porcentaje }} %.</b></h2>
                    <br>
                    <h2> Le quedan <b>2 (dos)</b> oportunidades más para rendir, debe esperar 5 dias habiles para poder rendir el proximo examen.</h2>
                    <br>
                    <h2>Si reprueba 3 veces deberá realizar nuevamente el trámite desde el inicio, tenga en cuenta que el curso tiene validez de un año.</h2>
                    <br>
                    <h2>El resultado de su examen quedará registrado y asentado en todos nuestros registros y sólo podrá rendir nuevamente dentro de <b>cinco días </b> corridos; a partir del día <b>24 de Mayo del 2017. </b></h2>
                    <br>
                    <h2> Recuerde que su trámite tiene una validez de  <b>90 días </b> corridos desde el día que lo inició: <b>14 de Marzo del 2017</b> y vence: <b>14 de Junio del 2017</b></h2>
                    <br>
                    <h2> <b>Si su trámite vence deberá iniciarlo nuevamente abonando todos los costos correspondientes.</b></h2>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <h2> <b>Por favor diríjase al frente del aula para ver cómo continuar su trámite con el Bedel del áula.</b></h2>
                    @else

                      <h1><b>Equipo bloqueado</b></h1>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-sm-3">
              </div>

            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

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
var url_reload_examen =  '{{ config('app.url') }}'+'/deve_teorico/public/';
setInterval(function () {
  location.href = url_reload_examen;
}, {{ config('global.RELOAD_BLOQUEO_TEORICO') }}
);
</script>





<script type="text/javascript">
var url_reload =  '{{ config('app.url') }}'+'/deve_teorico/public/verificarAsignacion';
  setInterval(function () {
    console.log('intentando')
    verificarAsignacion();
  }, 3000
  );
</script>

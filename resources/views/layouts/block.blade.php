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
           <h4> {!! $examen->mensaje !!}
             <b>{!! $examen->porcentaje !!} %.</b>
           </h4>
            @else
            <h4> {!! $examen->mensaje !!}
              <b>{!! $examen->porcentaje !!} %.</b>
            </h4>
            @endif
            <br>
           <h4> Le quedan <b>2 (dos)</b> oportunidades más para rendir, debe esperar 5 dias habiles para poder rendir el proximo examen.</h4>

           <h4>Si reprueba 3 veces deberá realizar nuevamente el trámite desde el inicio, tenga en cuenta que el curso tiene validez de un año.</h4>

           <h4>El resultado de su examen quedará registrado y asentado en todos nuestros registros y sólo podrá rendir nuevamente dentro de <b>cinco días </b> corridos; a partir del día <b>24 de Mayo del 2017. </b></h4>

           <h4> Recuerde que su trámite tiene una validez de  <b>90 días </b> corridos desde el día que lo inició: <b>14 de Marzo del 2017</b> y vence: <b>14 de Junio del 2017</b></h4>

           <h4> <b>Si su trámite vence deberá iniciarlo nuevamente abonando todos los costos correspondientes.</b></h4>
           <br>
           <br>
           <h4> <b>Por favor diríjase al frente del aula para ver cómo continuar su trámite con el Bedel del áula.</b></h4>
           @else

             <h1 style="color:#585757;"><b>Equipo bloqueado</b></h1>
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

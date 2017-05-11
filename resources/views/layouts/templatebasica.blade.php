<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Examen Teorico </title>

    <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <!-- NProgress -->


  </head>

  <body>


    <div class="container-fluid contenedor-full">
      <div class="row content">
        <div class="col-sm-12">
          <div class="contenedor-examen-panel panel panel-default ">
            <div class="panel-body text-center">
              <legend><h1>El examen a finalizado</h1></legend>
              <p>Resultado del examen: {{ $porcentaje }}</p>
              <p>{{ $mensaje }}</p>
              <p>Acerquese al bedel para recibir instrucciones</p>
            </div>
          </div>
        </div>
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

    @yield('scripts')

    <!-- Custom Theme Scripts
    <script src="{{ asset('build/js/custom.min.js')}}"></script>
-->
  </body>
</html>

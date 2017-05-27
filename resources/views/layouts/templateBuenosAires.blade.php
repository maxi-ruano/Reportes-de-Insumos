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
            @yield('content')
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

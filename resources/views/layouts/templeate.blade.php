<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Sistema De Licencias') }}</title>

    <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
   <link href="{{ asset('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    @yield('css')
    <!-- Custom Theme Style -->
    <link href="{{ asset('build/css/custom.min.css')}}" rel="stylesheet">
    <link href="{{ asset('build/css/custom.da.css')}}" rel="stylesheet">
    <link href="{{ asset('css/hover_effects.css') }}" rel="stylesheet">
    @yield('favicon')
  </head>

  <body class="nav-sm">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{ route('home') }}" class="site_title"><i class="fa fa-folder"></i> <span class="appclr">Licta</span></a>
            </div>
            <div class="clearfix"></div>
            <!-- menu profile quick info -->
              @include('includes.menuProfile')
            <!-- /menu profile quick info -->
            <br />
            <!-- sidebar menu -->
               @include('includes.slidebar')
            <!-- /sidebar menu -->
          </div>
        </div>
        <!-- top navigation -->
          @include('includes.header')
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="page-title">
            <div class="title_left">
              <h3>@yield('titlePage')</h3>
            </div>
            <div class="title_right">
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                  <!--<input type="text" class="form-control" placeholder="Search for...">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                  </span>
                  -->
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
            @if(isset($errors))
              @if(count($errors)>0)
                <div class="alert alert-danger">Errores
                  <ul>
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
            @endif
            
            @include('flash::message')
            @yield('content')
            @include('includes.modal')
          </div>
        <!-- /page content -->

        <!-- footer content -->
          @include('includes.footer')
        <!-- /footer content -->
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
    <!-- iCheck -->
    <script src="{{ asset('vendors/iCheck/icheck.min.js')}}"></script>
    @yield('scripts')
    @stack('scripts')

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('build/js/custom.min.js')}}"></script>

    <script>  
      $(document).ready(function(){
        setTimeout(function() {
          $('div.alert').fadeOut('fast');
        }, 5000); // <-- time in milliseconds
      });
    </script>
  
  </body>
</html>

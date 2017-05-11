<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Examen Teorico | </title>
     <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('vendors/animate.css/animate.min.css')}}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset('build/css/custom.min.css')}}" rel="stylesheet">

  </head>

  <body class="login">
    <div>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <h1>Ingrese su DNI</h1>
          {{ Form::open(['route' => 'rendir_examen', 'method' => 'POST', 'files' => false]) }}

              {{ csrf_field() }}
-             <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="dni" type="text" class="form-control" name="dni" value="{{ old('email') }}" placeholder="DNI">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-btn fa-sign-in"></i> Enviar
                </button>
              </div>
            {{ Form::close() }}
             <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-folder"></i> Examen Teorico</h1>
                </div>
              </div>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>

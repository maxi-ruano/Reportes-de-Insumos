<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
@if( isset($teoricopc) )
    {{ Form::open(['route' => ['computadoras.update', $teoricopc], 'method' => 'PUT', 'role' => 'form', 'files' => false]) }}
@else
{{ Form::open(['route' => 'computadoras.store', 'method' => 'POST', 'role' => 'form', 'files' => false]) }}
@endif
        <p>ip</p>
        <input type="text" name="ip" value="{{ isset($teoricopc) ? $teoricopc->ip : null }}">
        <p>sucursal_id</p>
        <input type="text" name="sucursal_id" value="{{ isset($teoricopc) ? $teoricopc->sucursal_id : null }}">
        <p>estado</p>
        <input type="text" name="estado" value="{{ isset($teoricopc) ? $teoricopc->estado : null }}">
        <input type="submit" value="enviar">
{{ Form::close() }}
    </body>
</html>

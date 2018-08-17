<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Error!</title>
        <link href="{{ asset('build/css/custom.da.css')}}" rel="stylesheet">
    </head>
    <body style="background-color:#333333; color:white; ">

        <div id="app">
            <nav class="navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <img src="http://cdn2.buenosaires.gob.ar/campanias/2015-1/img/bac.png" class="img-responsive" style="height: 50px;display: initial;">
                    </div>
                </div>
            </nav>

            <div style="text-align:center; color:white; margin-top:50px;">
                <h1> OOPS!!</h1>
                <img src="/img/page-error-personaje.png" style="width:50%; max-width:250;"> 
            </div>

            <div style="max-width:600px; margin:0px auto; padding:20px; font-family:sans-serif; text-align: justify;">   
                @yield('content')
                Por favor, para ir a la página de inicio haga <a href="{{ url('login')}}" style='color:#ffd300;'> click aqui</a>
            </div>
            
        </div>

    </body>
</html>
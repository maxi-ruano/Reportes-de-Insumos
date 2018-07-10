<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <img src="http://cdn2.buenosaires.gob.ar/campanias/2015-1/img/bac.png" class="img-responsive" style="height: 50px;display: initial;">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <b>Bienvenido {{ Auth::user()->name }} !</b>
            <img src="{{ asset('production/images/user.png')}}" onerror="this.src='{{ asset('production/images/user.png')}}'" alt="">
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Salir</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
            </li>
          </ul>
        </li>

        <!-- Mostrar Notificaciones - Alertas -->
        <!--
        <li role="presentation" class="dropdown">
          <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell"></i>
            <span class="badge bg-green">6</span>
          </a>
          <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
            <li>
              <a>
                <span class="image"><img src="{{ asset('production/images/img.jpg')}}" alt="Profile Image" /></span>
                <span>
                  <span>Juan Carlos</span>
                  <span class="time">3 mins ago</span>
                </span>
                <span class="message">
                  Ah creado un nuevo Cliente...
                </span>
              </a>
            </li>
            <li>
              <a>
                <span class="image"><img src="{{ asset('production/images/img.jpg')}}" alt="Profile Image" /></span>
                <span>
                  <span>Juan Carlos</span>
                  <span class="time">3 mins ago</span>
                </span>
                <span class="message">
                  Ah Eliminado un Cliente...
                </span>
              </a>
            </li>
            <li>
              <a>
                <span class="image"><img src="{{ asset('production/images/img.jpg')}}" alt="Profile Image" /></span>
                <span>
                  <span>Juan Carlos</span>
                  <span class="time">3 mins ago</span>
                </span>
                <span class="message">
                  Ah Modificado un Referente...
                </span>
              </a>
            </li>
            <li>
              <a>
                <span class="image"><img src="{{ asset('production/images/img.jpg')}}" alt="Profile Image" /></span>
                <span>
                  <span>Juan Carlos</span>
                  <span class="time">3 mins ago</span>
                </span>
                <span class="message">
                  Ah Despachado documentos del cliente Viva...
                </span>
              </a>
            </li>
            <li>
              <div class="text-center">
                <a>
                  <strong>Ver todas las Alertas</strong>
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
            </li>
          </ul>
        </li>
        -->
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->

<!-- verificar si el usuario no se encuentra Autenticado-->
@if (Auth::guest())
 <div class="top_nav">
   <div class="nav_menu">
      <nav>
           <!-- Right Side Of Navbar -->
           <ul class="nav navbar-nav navbar-right">
      	   	<li><a href="{{ route('login') }}">Login</a></li>
      	   </ul>
      </nav>
    </div>
 </div>
@else

 <!-- left column Menu-->
 <div class="col-md-3 left_col">
    <div class="left_col scroll-view">
       <div class="navbar nav_title" style="border: 0;">
          <a href="{{ route('home') }}" class="site_title"><i class="fa fa-folder"></i><span class="appclr">SATH</span></a>
       </div>
       <div class="clearfix"></div>
	<!-- menu profile quick info -->
        @include('includes.menuProfile')
        <!-- sidebar menu -->
        @include('includes.slidebar')
    </div>
 </div>
 <!-- /left column Menu-->
 <!-- top navigation -->
 <div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>
      <ul class="nav navbar-nav navbar-right">
         <!-- <img src="http://cdn2.buenosaires.gob.ar/campanias/2015-1/img/bac.png" class="img-responsive" style="height: 50px;display: initial;"> -->
	 <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <b>Bienvenido {{ isset(Auth::user()->name)?Auth::user()->name:'' }}!</b>
            <img src="{{ asset('production/images/user.png')}}" onerror="this.src='{{ asset('production/images/user.png')}}'" alt="">
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li>
                <a href="{{ route('changePassword') }}"><i class="fa fa-unlock-alt"></i>Cambiar Password</a>
            </li>
            <li>
		<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
		  <i class="fa fa-sign-out"></i>Salir
		</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
            </li>
          </ul>
	 </li>
      </ul>
    </nav>
  </div>
 </div>
 <!-- /top navigation -->
@endif

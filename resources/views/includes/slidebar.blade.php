
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>Clientes</h3>
    <ul class="nav side-menu">
      @if(session('usuario_rol_id') == '40' || session('usuario_rol') == 'ROL_ESCUELA' || session('usuario_rol') == 'ROL_ADMIN'
	|| session('usuario_id') == '2722' || session('usuario_id') == '2790' || session('usuario_id') == '2432')
      <li><a href="{{ route('bedel.index') }}">
        <i class="fa fa-users"></i> Bedel
        <span class="fa fa-chevron-down"></span></a>
      </li>
      @endif
      @if( session('usuario_rol') == 'ROL_ADMIN' || session('usuario_rol') == 'ROL_DISPOSICIONES')
      <li>
        <a href="{{ route('disposiciones.index') }}">
          <i class="fa fa-file"></i> Disposiciones
          <span class="fa fa-chevron-down"></span>
        </a>
      </li>
      @endif
    </ul>
  </div>

  </div>
<!-- /menu footer buttons -->
<div class="sidebar-footer hidden-small">
  <a data-toggle="tooltip" data-placement="top" title="Settings">
    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="Lock">
    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="Logout">
    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
  </a>
</div>
<!-- /menu footer buttons -->

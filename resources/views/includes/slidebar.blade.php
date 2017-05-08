
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>Clientes</h3>
    <ul class="nav side-menu">
      <li><a><i class="fa fa-users"></i> Clientes <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('sysfile.customers.index')}}">Lista Cliente</a></li>
          <li><a href="{{ route('sysfile.customers.create')}}">Crear Cliente</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-suitcase"></i> Departamentos Cli. <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('sysfile.departments.index')}}">Lista Departamentos</a></li>
          <li><a href="{{ route('sysfile.departments.create')}}">Crear Departamento</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-male"></i> Referentes Cli. <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('sysfile.referents.index')}}">Lista Referentes</a></li>
          <li><a href="{{ route('sysfile.referents.create')}}">Crear Referente</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-user"></i> Direcciones Cli. <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('sysfile.addresses.index')}}">Lista Direcciones</a></li>
          <li><a href="{{ route('sysfile.addresses.create')}}">Crear Direccion</a></li>
        </ul>
      </li>
    </ul>
  </div>
   <div class="menu_section">
    <h3>Administracion</h3>
    <ul class="nav side-menu">
      <li><a><i class="fa fa-building"></i> Depositos <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('sysfile.deposits.index')}}">Lista Depositos</a></li>
          <li><a href="{{ route('sysfile.deposits.create')}}">Crear Deposito</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-user"></i> Operadores <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('sysfile.operators.index')}}">Lista Operadores</a></li>
          <li><a href="{{ route('sysfile.operators.create')}}">Crear Operador</a></li>
        </ul>
      </li>
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

           
           
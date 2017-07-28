@extends('layouts.templeate')
@section('titlePage', 'Control Secuencia Insumos')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">

        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Ajustes 1</a>
              </li>
              <li><a href="#">Ajustes 2</a>
              </li>
            </ul>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">

        <div class="form-group text-center">

          <div class="col-md-12">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                <input value="pausar" id="pausarReload" type="radio"> &nbsp; Pausar refresh &nbsp;
              </label>
              <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                <input value="start" id="startReload"  type="radio"> Activar refresh
              </label>
            </div>
          </div>
        </div>
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Sucursal</th>
              <th>Usuario</th>
              <th>Ultimo Insumo</th>
              <th>Insumo Fallado</th>
              <th class='text-center'>Justificado</th>
              <th>Fecha registro</th>
              <th class='text-center'>Acciones</th>
            </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
          <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->sucursal }}</td>
            <td>{{ $item->userName }}</td>
            <td>{{ $item->insumo_ultimo }}</td>
            <td>{{ $item->insumo_intento_insercion }}</td>
            <td class='text-center'>
                @if( empty($item->justificado))<h4><span class='label label-danger' >No Justificado</span></h4>
                @else <h4><span class='label label-success' >Justificado</span></h4>
                @endif
            </td>
            <td >{{ $item->created_at }}</td>
            <td class='text-center'>
              @if( empty($item->justificado) )
                <a href="{{ route('justificaciones.edit', $item->id)}}" type="button" class="btn btn-primary btn-sm">Justificar</a>
              @else
                <a href="#" type="button" class="btn btn-primary btn-sm" disabled>Justificar</a>
              @endif
              <a href="{{ route('justificaciones.show', $item->id)}}" type="button" class="btn btn-info btn-sm" >Ver</a>
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- /page content -->
@endsection

@section('scripts')
<!-- validator -->
<script src="{{ asset('vendors/validator/validator.js')}}"></script>
@include('includes.scriptForms')
<!-- Datatables -->
<script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
<script src="{{ asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
<script src="{{ asset('vendors/jszip/dist/jszip.min.js')}}"></script>
<script src="{{ asset('vendors/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js')}}"></script>
<script>
var automatico = true;

function reload(){
  if(automatico)
    window.location.reload(1);
}

  $(document).ready(function() {
    $('#datatable-responsive').DataTable({order: [[0, "desc"]]});

    $("#pausarReload, #startReload").change(function () {
      if (this.value == 'pausar')
        automatico = false;
      if(this.value == 'start')
        automatico = true;
    });

    window.setInterval(reload , 5000);
  });
</script>
@endsection
@section('css')
<!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
@endsection

@extends('layouts.templeate')
@section('titlePage', 'Disposiciones')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <a href="{{ route('disposiciones.create') }}" type="button" class="btn btn-primary">Crear Disposicion</a>
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
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID Disposicion</th>
              <th>Descripcion</th>
              <th>Tramite</th>
              <th>Otorgante</th>
              <th>Creado en</th>
            </tr>
          </thead>
          <tbody>
          @foreach($disposiciones as $disposicion)
          <tr>
            <td>{{ $disposicion->id }}</td>
            <td>{{ $disposicion->descripcion }}</td>
            <td>{{ $disposicion->tramite_id }}</td>
            <td>{{ $disposicion->user->first_name.' '.$disposicion->user->last_name }}</td>
            <td>{{ $disposicion->created_at }}</td>

            <!--<td>
              <div class="text-center dropdown" id="user-header">
                  <button type="button" class="btn btn-sm btn-primary" aria-expanded="false"href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                      <i class="glyphicon glyphicon-th-list">&nbsp;</i><span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <a href="{{ route('disposiciones.edit', $disposicion->id)}}"><i class="fa fa-check-square"></i><span> Otorgar</span></a>
                    </li>
                    <li>
                      <a href="{{ route('disposiciones.edit', $disposicion->id)}}"><i class="fa fa-minus-square"></i><span> Denegar</span></a>
                    </li>
                    <li>
                      <a href="{{ route('disposiciones.destroy', $disposicion->id)}}"><i  class="glyphicon glyphicon-remove"></i><span> Borrar</span></a>
                    </li>
                  </ul>
              </div>
            </td> -->
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
  $(document).ready(function() {
    $('#datatable-responsive').DataTable({order: [[0, "desc"]]});

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

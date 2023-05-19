@extends('layouts.templeate')
@section('titlePage', 'Control Insumos')
@section('content')
<!-- page content -->
<br>
<br>


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
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Lote_id</th>
              <th>Sucursal_id</th>
              <th>Control desde</th>
              <th>Control hasta</th>
              <th>Habilitado</th>
              <th> N° Kit</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($insumos as $insumo)
            <tr>
                <td>{{ $insumo->lote_id }}</td>
                <td>{{ $insumo->sucursal_id }}</td>
                <td>{{ $insumo->control_desde }}</td>
                <td>{{ $insumo->control_hasta}}</td>
                <td>{{ $insumo->habilitado}}</td>
                <td>{{ $insumo->nro_kit}}</td>
            </tr>
        @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- <button id="descargarExcel" class="btn btn-primary">descargar Excel</button> --}}

<button id="descargarExcel" class="btn btn-primary">descargar Excel</button>
<p>
Click <a href="{{route('exportar.insumos')}}"> aqui </a>

</p>
<!-- /page content -->
@endsection

@section('scripts')


<script>
  document.getElementById('descargarExcel').addEventListener('click', function() {
      // Realizar una solicitud AJAX al servidor
      var xhr = new XMLHttpRequest();
      xhr.open('GET', '{{ route("exportar.insumos") }}', true);
      xhr.responseType = 'blob';

      xhr.onload = function() {
          if (this.status === 200) {
              // Crear un objeto URL con los datos del archivo
              var blobUrl = window.URL.createObjectURL(this.response);

              // Crear un enlace y hacer clic en él para descargar el archivo
              var a = document.createElement('a');
              a.href = blobUrl;
              a.download = 'insumos.xlsx';
              a.style.display = 'none';
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
          }
      };

      xhr.send();
  });
</script>




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

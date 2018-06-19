@extends('layouts.templeate')
@section('titlePage', 'Personas en Espera')
@section('content')

<!-- page content -->

  <div class="right_col" role="main">
  
    
    <!-- /Form Group -->
    <div class="form-group">
      <div class="col-md-5 col-sm-12 col-xs-12">
        {!! Form::open(['route' => 'consultaDashboardGraf', 'id'=>'consultaDashboardGraf', 'method' => 'get', 'class' => 'form-horizontal form-label-left', 'role' => 'form', 'files' => true ]) !!}
          @include('safit.botoneraDashboard')
        {{ Form::close() }}

        <!-- /Grafica - echart_donut -->
        <div class="x_panel">
          <div class="x_title">
            <h2>Sucursal Roca </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div id="echart_sedeRoca" style="height:600px;"></div>
          </div>
        </div>
      </div>

    </div>
    <!-- /end form-group -->

    <!-- /Grafica - echart por Sucursal -->
    <div class="col-md-7 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Por Sucursal</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div id="echart_sedes" style="height:650px;"> </div> <br><br>
      </div>
    </div>
    <!-- /Grafica echart -->
    
  </div>
<!-- /page content -->

@endsection

@push('scripts')
  <script src="{{ asset('vendors/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

  <!-- bootstrap-daterangepicker -->
  <script src="{{ asset('vendors/moment/min/moment.min.js')}}"></script>
  <script src="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

  <!-- Template Gentelella ECharts - Graph -->
  <script src="{{ asset('vendors/echarts/echarts.min.js')}}"></script>
  <script src="{{ asset('js/dashboard.js')}}"></script>

  <script>
    function reload(){
      window.location.href = "{{ route('consultaDashboard') }}"; //using a named route
    }
  </script>
@endpush

@section('css')
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection 
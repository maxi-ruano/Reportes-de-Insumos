@extends('layouts.templeate')
@section('titlePage', 'Personas en Espera')
@section('content')

<!-- page content -->

  <div role="main">
    
    <!-- /Grafica - echart_donut -->
    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
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
          <div id="echart_sedeRoca" style="height:630px;"></div>
        </div>
      </div>
    </div>

    <!-- /Grafica - echart por Sucursal -->
    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
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
        <div class="x_content">
            <div id="echart_sedes" style="height:630px;"> </div>
        </div>
      </div>
    </div>
    <!-- /Grafica echart -->
    
    <!-- /Form Group -->
    <div class="col-md-12 col-sm-12 col-xs-12">
        {!! Form::open(['route' => 'consultaDashboardGraf', 'id'=>'consultaDashboardGraf', 'method' => 'get', 'class' => 'form-horizontal form-label-left', 'role' => 'form', 'files' => true ]) !!}
          @include('safit.botoneraDashboard')
        {{ Form::close() }}
    </div>
    <!-- /end form-group -->
    
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

  <!-- Bootstrap-toggle -->
  <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>

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
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endsection 
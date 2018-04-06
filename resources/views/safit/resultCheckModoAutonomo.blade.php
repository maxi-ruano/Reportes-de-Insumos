@extends('layouts.templeate')
@section('titlePage', 'Resultado Pre-Check')
@section('content')
<!-- page content -->
        <div class="row">
          @include('safit.botoneraPrecheck')
        </div>
        <div class="row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">
          </label>

              <div class="col-md-6 col-sm-6 col-xs-12">
              @if (!empty($error))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <strong>{{ $error }}</strong>
                </div>
              @endif
              @if (!empty($mensaje))
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <strong>{{ $mensaje }}</strong>
                </div>
              @endif
            </div>
          

        </div>
        <div class="clearfix"></div>
        <div class="row">
          <div class="col-md-6">
            <div class="x_panel">
              <div class="x_title">
                <h2>Datos Tramite <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                  <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="x_panel">
              <div class="x_title">
                <h2>Log de Pre-Check <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Settings 1</a>
                      </li>
                      <li><a href="#">Settings 2</a>
                      </li>
                    </ul>
                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <ul>
                @if(!is_null($tramite))
                  <li>
                  @if ($tramite->estado >= 3)
                      <label class="btn btn-success">Cenat Emitido</label>
                  @else
                      <label class="btn btn-danger">Cenat no Emitido</label>
                  @endif
                  </li>
                  <li>
                  @if ($tramite->estado >= 4)
                      <label class="btn btn-success">Libre Deuda Verificado</label>
                  @else
                      <label class="btn btn-danger">Libre Deuda NO Verificado </label>
                  @endif
                  </li>
                  <li>
                  @if ($tramite->estado >= 5)
                      <label class="btn btn-success">BUI Verificado</label>
                  @else
                      <label class="btn btn-danger">BUI No Verificado </label>
                  @endif
                  </li>
                @endif
                </ul>
                {{--
                @if (false)
                @foreach($log as  $value)
                  <article class="media event">
                    <a class="pull-left date">
                    <!--  <p class="month">{{$value->estado_error}}</p> -->
                      <p class="day">{{$value->id}}</p>
                    </a>
                    <div class="media-body">
                      <a class="title" href="#">{{$value->estado_error }} - {{$value->textEstado() }} </a><small> {{$value->created_at}}</small>
                      <p>{{$value->description}}</p>
                    </div>
                  </article>
                  @endforeach
                @endif
                --}}
              </div>
            </div>
          </div>
        </div>


<!-- /page content -->
@endsection

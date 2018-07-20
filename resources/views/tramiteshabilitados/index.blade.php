@extends('layouts.templeate')
@section('titlePage', 'Tramites Habilitados')
@section('content')
<!-- page content -->

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            {!! Form::open(['method'=>'GET','url'=>'tramitesHabilitados','class'=>'navbar-form navbar-left','role'=>'search'])  !!}
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Buscar..." value="{{ Request::get('search') }}">
                <span class="input-group-btn">
                    <input type="date" class="form-control" name="fecha" id="fecha" value={{ isset($_GET['fecha'])?$_GET['fecha']:date('Y-m-d') }}>
                    <button id="buscar" class="btn btn-default-sm" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="col-sm-4 col-xs-12 text-right">
            <a href="{{route('tramitesHabilitados.index')}}" class="btn btn-primary"> Actualizar <i class="glyphicon glyphicon-refresh"></i> </a>
            @can('add_tramitesHabilitados')
                <a href="{{route('tramitesHabilitados.create')}}" class="btn btn-primary">Nuevo <i class="glyphicon glyphicon-plus-sign"></i> </a>
            @endcan
        </div>        
    </div>

    <div class="table-responsive">
    @if($data)
        <table class="table table-striped jambo_table">
            <thead>
                <tr>
                    <th class="column-title">Apellido</th>
                    <th class="column-title">Nombre</th>
                    <th class="column-title">Tipo Doc.</th>
                    <th class="column-title">Nro. Doc.</th>
                    <th class="column-title">Pais</th>
                    <th class="column-title">Fecha</th>
                    <th class="column-title">Motivo</th>
                    <th class="column-title">Usuario</th>
                    <th class="column-title">Habilitado</th>
                    <th class="column-title"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->apellido }}</td>
                    <td>{{ $row->nombre }}</td>
                    <td>{{ $row->tipo_doc }}</td>
                    <td>{{ $row->nro_doc }}</td>
                    <td>{{ $row->pais }}</td>
                    <td>{{ $row->fecha }}</span>
                    <td>{{ $row->motivo_id }} </td>
                    <td>
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Creado {{ $row->created_at }}">    
                            {{ $row->user_id }}
                        </span>
                    </td>
                    <td>
                        @if($row->habilitado)
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{ $row->habilitado_user_id }}">    
                            @if(Auth::user()->hasRole('Admin'))
                                <input id="habilitado{{ $row->id }}" type="checkbox" checked onchange="habilitarTurno({{ $row->id }})" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                            @else
                                <input id="habilitado{{ $row->id }}" type="checkbox" checked disabled data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                            @endif
                        </span> 
                        @else
                            <input id="habilitado{{ $row->id }}" type="checkbox" onchange="habilitarTurno({{ $row->id }})" data-toggle="toggle"  data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60" >
                        @endif
                    </td>                                                
                    <td>
                        @can('edit_tramitesHabilitados')
                            <a href="{{ route('tramitesHabilitados.edit', $row->id) }}" class="btn btn-success pull-right btn-xs" title="Editar"> Editar <i class="fa fa-edit"></i></a>
                        @endcan

                        @can('delete_tramitesHabilitados')
                            {!! Form::open(array('route' => array('tramitesHabilitados.destroy', $row->id), 'method' => 'delete')) !!}
                                <button class='btn btn-danger pull-right btn-xs' type="submit"> Borrar <i class="fa fa-trash"></i> </button>
                            {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    </div>

    <div class="col-sm-12 col-xs-12 text-center">
        {{ $data->links() }}
    </div>

</div>

<!-- /page content -->
@endsection


@push('scripts')
    <!-- Bootstrap-toggle -->
    <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            
            @if(!isset($_GET['page']) && !isset($_GET['search']))
                establecerTimeout();
            @endif

            @if(isset($_GET['search']))
                @if($_GET['search'] == '')
                    establecerTimeout();
                @endif
            @endif

            @if(isset($_GET['page']))
                @if($_GET['page'] == '1')
                    establecerTimeout();
                @endif
            @endif
        });

        function habilitarTurno(id){
            var valor = $("#habilitado"+id).prop('checked');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '/tramitesHabilitadosHabilitar',
                data: {id: id, valor:valor },
                type: "GET", 
                success: function(ret){
                    cargarPagina();
                }
            });
        }

        function establecerTimeout(){
            setTimeout(cargarPagina, 60000);
        }

        function cargarPagina(){
            window.location.href = "{{ route('tramitesHabilitados.index') }}";
        }
    </script>
@endpush

@section('css')
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endsection 
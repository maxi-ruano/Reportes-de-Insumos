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
                    <input type="date" class="form-control" name="fecha" id="fecha" value={{ isset($_GET['fecha'])?$_GET['fecha']:'' }}>
                    {!! Form::select('sucursal', $sucursales, null , ['class' => 'form-control', 'placeholder' => 'Todas las Sucursales']) !!}                
                    <button id="buscar" class="btn btn-default-sm" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="col-sm-4 col-xs-12 text-right">
            <a href="javascript:void(0)" onclick="cargarPagina()" class="btn btn-primary"> Actualizar <i class="glyphicon glyphicon-refresh"></i> </a>
            @can('add_tramites_habilitados')
                <a href="{{route('tramitesHabilitados.create')}}" class="btn btn-primary">Nuevo <i class="glyphicon glyphicon-plus-sign"></i> </a>
            @endcan
        </div>        
    </div>

    <div class="table-responsive">
    @if($data)
        <table class="table table-striped jambo_table" data-form="deleteForm">
            <thead>
                <tr>
                    <th class="column-title">Apellido</th>
                    <th class="column-title">Nombre</th>
                    <th class="column-title">Tipo Doc.</th>
                    <th class="column-title">Nro. Doc.</th>
                    <th class="column-title">Pais</th>
                    <th class="column-title">Fecha</th>
                    <th class="column-title">Sucursal</th>
                    <th class="column-title">Motivo</th>
                    <th class="column-title">Usuario</th>
                    <!-- Establecer condicion para mostrar u ocultar el boton Habilitar -->
                    @can('habilita_tramites_habilitados')
                        <th class="column-title" style="width:auto!important;">Habilitado</th>
                    @endcan
                    <th class="column-title" style="width:auto!important;">Precheck</th>
                    @can('edit_tramites_habilitados','delete_tramites_habilitados')
                        <th class="column-title" style="width:80px!important;"></th>
                    @endcan
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
                    <td>{{ $row->sucursal }}</span>
                    <td>
                        @if ($row->observacion)
                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{ $row->observacion }}">    
                                {{ $row->motivo_id }}
                            </span>
                        @else
                            @if ($row->sigeci_idcita)
                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Nro. Cita {{ $row->sigeci_idcita }}">    
                                {{ $row->motivo_id }}
                            </span>
                            @else
                                {{ $row->motivo_id }}
                            @endif
                        @endif
                    </td>
                    <td>
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Creado {{ $row->created_at }} por el Rol: {{ $row->rol }}">    
                            {{ $row->user_id }}
                        </span>
                    </td>
                    @can('habilita_tramites_habilitados')
                    <td>
                        @php $disable_not_today = ($row->fecha == date('d-m-Y'))?'':'disabled' @endphp
                        @if($row->habilitado)
                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{ $row->habilitado_user_id }}">    
                                @if(Auth::user()->hasRole('Admin'))
                                    <input id="habilitado{{ $row->id }}" type="checkbox" checked {{ $disable_not_today }} onchange="habilitarTurno({{ $row->id }})" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                                @else
                                    <input id="habilitado{{ $row->id }}" type="checkbox" checked disabled data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                                @endif
                            </span> 
                        @else
                            <input id="habilitado{{ $row->id }}" type="checkbox" {{ $disable_not_today }} onchange="habilitarTurno({{ $row->id }})" data-toggle="toggle"  data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60" >
                        @endif
                    </td>
                    @endcan
                    <td>
                        <button type="button" onclick="mostrarPrecheck({{ $row->tramites_a_iniciar_id }})" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-precheck">
                            <i class="glyphicon glyphicon-check"></i> Precheck
                        </button>
                    </td>
                    @can('edit_tramites_habilitados','delete_tramites_habilitados')
                    <td>
                        <div class="btn-toolbar" role="toolbar">
                            @can('edit_tramites_habilitados')
                                <a href="{{ route('tramitesHabilitados.edit', $row->id) }}" class="btn btn-success btn-xs" title="Editar"> <i class="fa fa-edit"></i></a>
                            @endcan
                            @can('delete_tramites_habilitados')
                                {!! Form::open(array('route' => array('tramitesHabilitados.destroy', $row->id), 'method' => 'delete', 'class' => 'form-delete')) !!}
                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                {!! Form::close() !!}
                            @endcan
                        </div>
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    </div>

    <div class="col-sm-12 col-xs-12 text-center">
        {{ $data->appends(request()->query())->links() }}
    </div>

</div>
@include('includes.modalPrecheck')
<!-- /page content -->
@endsection


@push('scripts')
    <!-- Bootstrap-toggle -->
    <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            habilitado = true;
            //Actualizar la pagina automaticamente solo si no han realizado busqueda o cambiado de paginacion
            @if(!isset($_GET['page']) && !isset($_GET['search']))
                iniciarRegargarPagina();
            @endif

            @if(isset($_GET['search']) && !isset($_GET['fecha']))
                @if($_GET['search'] == '')
                    iniciarRegargarPagina();
                @endif
            @endif

            @if(isset($_GET['page']))
                @if($_GET['page'] == '1')
                    iniciarRegargarPagina();
                @endif
	     @endif

	     $('#modal-precheck').on('hidden.bs.modal', function () {
		     iniciarRegargarPagina()
	     })

        });
        
        function habilitarTurno(id){
            var valor = $("#habilitado"+id).prop('checked');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'tramitesHabilitadosHabilitar',
                data: {id: id, valor:valor },
                type: "GET", 
                success: function(ret){
                    cargarPagina();
                }
            });
        }

        function cargarPagina(){            
            $("#buscar").click();
        }

        var recargarPagina;

        function iniciarRegargarPagina() {
            recargarPagina = setTimeout(cargarPagina, 60000);
            console.log('se inicio')
        }

        function detenerRegargarPagina() {
            clearTimeout(recargarPagina);
            console.log('se detuvo')
        }
        
        //Mostrar el Precheck realizado del tramites_a_iniciar_id
        function mostrarPrecheck(id){            
            detenerRegargarPagina();
            getPreCheck(id);

        }

    </script>
@endpush

@section('css')
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/precheck.css') }}" rel="stylesheet">
@endsection 

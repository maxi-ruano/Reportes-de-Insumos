@extends('layouts.templeate')
@section('titlePage', 'Motivos')
@section('content')
<!-- page content -->

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            {!! Form::open(['method'=>'GET','url'=>'tramitesHabilitadosMotivos','class'=>'navbar-form navbar-left','role'=>'search'])  !!}
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Buscar..." value="{{ Request::get('search') }}">
                <span class="input-group-btn">
                    <button id="buscar" class="btn btn-default-sm" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-sm-4 col-xs-12 text-right">
                <a href="{{route('roleMotivos.index')}}" class="btn btn-success"> <i class="fa fa-edit"></i> Motivos por Rol </a>
            @can('add_tramites_habilitados_motivos')
                <a href="{{route('tramitesHabilitadosMotivos.create')}}" class="btn btn-primary">Nuevo <i class="glyphicon glyphicon-plus-sign"></i> </a>
            @endcan
        </div>        
    </div>

    <div class="table-responsive">
    @if($data)
        <table class="table table-striped jambo_table" data-form="deleteForm">
            <thead>
                <tr>
                    <th class="column-title">Id</th>
                    <th class="column-title">Motivo</th>
                    <th class="column-title">Limite</th>
                    <th class="column-title">Sucursal</th>
                    <th class="column-title">Activo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->description }}</td>
                    <td>{{ $row->limite }}</td>
                    <td>{{ $row->sucursal }}</td>
                    <td>
                        @if($row->activo)
                            <input id="motivo{{ $row->id }}" type="checkbox" checked onchange="habilitarMotivo({{ $row->id }})" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60">
                        @else
                            <input id="motivo{{ $row->id }}" type="checkbox" onchange="habilitarMotivo({{ $row->id }})" data-toggle="toggle"  data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60" >
                        @endif
                    </td>
                    @can('edit_tramites_habilitados_motivos','delete_tramites_habilitados_motivos')
                    <td>
                        <div class="btn-toolbar" role="toolbar">
                            @can('edit_tramites_habilitados')
                                <a href="{{ route('tramitesHabilitadosMotivos.edit', $row->id) }}" class="btn btn-success btn-xs" title="Editar"> <i class="fa fa-edit"></i></a>
                            @endcan
                            @can('delete_tramites_habilitados_motivos')
                                {!! Form::open(array('route' => array('tramitesHabilitadosMotivos.destroy', $row->id), 'method' => 'delete', 'class' => 'form-delete')) !!}
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

<!-- /page content -->
@endsection

@push('scripts')
    <!-- Bootstrap-toggle -->
    <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script>
        function habilitarMotivo(id){
            var activo = $("#motivo"+id).prop('checked');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'tramitesHabilitadosMotivosHabilitar',
                data: {id: id, activo:activo },
                type: "GET", 
                success: function(ret){
                    $("#buscar").click();
                }
            });
        }
    </script>
@endpush

@section('css')
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/precheck.css') }}" rel="stylesheet">
@endsection
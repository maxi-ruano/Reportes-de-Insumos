@extends('layouts.templeate')
@section('titlePage', 'Usuarios')

@section('content')
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            {!! Form::open(['method'=>'GET','url'=>'users','class'=>'navbar-form navbar-left','role'=>'search'])  !!}
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Buscar..." value="{{ Request::get('search') }}">
                <span class="input-group-btn">
                    {!! Form::select('sucursal', $sucursales, null , ['class' => 'form-control', 'placeholder' => 'Todas las Sucursales']) !!}                
                    <button id="buscar" class="btn btn-default-sm" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
            
            {!! Form::close() !!}
        </div>
        
        <div class="col-md-4 page-action text-right">
                @can('view_roles')
                    <a href="{{ route('roles.index') }}" class="btn btn-success btn-sm">
                        <span class="glyphicon glyphicon-lock"></span> Roles
                    </a>
                @endcan 

            @can('add_users')
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> Nuevo </a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover jambo_table" id="data-table" data-form="deleteForm">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Sucursal</th>
                <th>Created At</th>
                <th>Activo</th>
                @can('edit_users', 'delete_users')
                    <th class="text-center">Actions</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->roles->implode('name', ', ') }}</td>
                    <td>{{ $item->sucursal }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    <td class="text-center">
                    
                        @if(Auth::user()->hasRole('Admin'))
                            @if($item->activo)
                                <input id="activo{{ $item->id }}" type="checkbox" onchange="activarCuenta({{ $item->id }})" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60" checked>
                            @else
                                <input id="activo{{ $item->id }}" type="checkbox"  onchange="activarCuenta({{ $item->id }})" data-toggle="toggle"  data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" data-width="60" >
                            @endif

                        @endif
                    </td>
                    
                    @can('edit_users')
                    <td class="text-center">
                        @if(Auth::user()->hasRole('Admin'))
                            @include('shared._actions', [
                                'entity' => 'users',
                                'id' => $item->id
                            ])
                        @else
                            @if ($item->roles->first()->name != 'Admin')
                                @include('shared._actions', [
                                    'entity' => 'users',
                                    'id' => $item->id
                                ])
                            @endif
                        @endif
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-left col-md-8">
            {{ $result->links() }}
        </div>

        <div class="text-right col-md-4">
            <h2 class="modal-title">Total {{ $result->total() }} {{ str_plural('registros', $result->count()) }} </h2>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function activarCuenta(id){
            var activo = $("#activo"+id).prop('checked');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: 'activarCuentaUser',
                data: {id: id, activo: activo },
                type: "GET", 
                success: function(ret){
                    $("#buscar").click();
                }
            });
        }    
    </script>

    <!-- Bootstrap-toggle -->
    <script src="{{ asset('vendors/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
@endpush

@section('css')
    <!-- bootstrap-toggle -->
    <link href="{{ asset('vendors/bootstrap-toggle/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
@endsection 
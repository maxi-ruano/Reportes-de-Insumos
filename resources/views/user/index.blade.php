@extends('layouts.templeate')
@section('titlePage', 'Usuarios')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h2 class="modal-title">Total {{ $result->total() }} {{ str_plural('registros', $result->count()) }} </h2>
        </div>
        <div class="col-md-7 page-action text-right">
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
                <th>Created At</th>
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
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    
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

        <div class="text-center">
            {{ $result->links() }}
        </div>
    </div>

@endsection

    
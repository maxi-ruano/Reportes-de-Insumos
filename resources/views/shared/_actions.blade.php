@can('edit_users')
    <a href="{{ route($entity.'.edit', [str_singular($entity) => $id])  }}" class="btn btn-xs btn-info">
        <i class="fa fa-edit"></i> Editar</a>
@endcan

@can('delete_users')
    {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => $id]), 'style' => 'display: inline']) !!}
        <!-- <button type="submit" onclick=" return confirm('probando') " class="btn-delete btn btn-xs btn-danger" > <i class="glyphicon glyphicon-trash"></i> </button> -->

        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal{{$id}}">
            <i class="glyphicon glyphicon-trash"></i> Borrar 
        </button>
        @include('includes.modal')
    {!! Form::close() !!}
@endcan
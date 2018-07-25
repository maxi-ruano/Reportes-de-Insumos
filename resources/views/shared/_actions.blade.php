@can('edit_users')
    <a href="{{ route($entity.'.edit', [str_singular($entity) => $id])  }}" class="btn btn-xs btn-success">
        <i class="fa fa-edit"></i> Editar
    </a>
@endcan

@can('delete_users')
    {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => $id]), 'class' => 'form-delete', 'style' => 'display: inline']) !!}
        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete">
            <i class="glyphicon glyphicon-trash"></i> Borrar 
        </button>
    {!! Form::close() !!}
@endcan
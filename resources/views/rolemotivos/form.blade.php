@extends('layouts.templeate')
@section('titlePage', "Asignar Motivos a un Rol")

@section('content')
<!-- page content -->
<div class="container">
    {!! Form::open(['route' => 'roleMotivos.store', 'id'=>'formRoleMotivos', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    
        <div class="form-group col-md-12 col-xs-12">                
            {!! Form::label('role_id', ' Rol de Usuario') !!}
            {!! Form::select('role_id', $roles, null , ['class' => 'form-control', 'placeholder' => 'Seleccione', 'required' => 'required']) !!}                
        </div>

        <div class="form-group col-md-6 col-xs-12">
            {!! Form::label('motivos_select[]', 'Motivos a mostrar en el Select') !!}
            {!! Form::select('motivos_select[]', $motivos, ($motivos_select!='') ? $motivos_select->pluck('motivo_id')->toArray() : null,  ['class' => 'form-control', 'size' => '20', 'multiple']) !!}
            <span>*** Cuando se registra un nuevo Tramite Habilitado se visualizara solo los seleccionados</span>         
        </div>

        <div class="form-group col-md-6 col-xs-12">
            {!! Form::label('motivos_list[]', 'Motivos a filtrar en el listado') !!}
            {!! Form::select('motivos_list[]', $motivos, ($motivos_list!='') ? $motivos_list->pluck('motivo_id')->toArray() : null,  ['class' => 'form-control',  'size' => '20',  'multiple']) !!}
            <span>*** Se aplica en el filtro para listar los Tramites Habilitados</span>
        </div>
        
        <div class="form-group col-md-12 col-xs-12">
            <hr>
            <button type="submit" class="btn btn-primary btn-group-justified"> <i class="fa fa-check-square-o"></i> Actualizar </button>                
        </div>
    {!! Form::close() !!}
</div>

<!-- /page content -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("select[name='role_id']").change(function(){
                var role_id = $(this).val();
                $("select[name='motivos_select[]'] option:selected, select[name='motivos_list[]'] option:selected").removeAttr("selected");
                if(role_id){
                    $("#formRoleMotivos").attr('route','roleMotivos.index').attr('method','GET');
                    $("button[type='submit']").click();
                }                
            });
        });
    </script>
@endpush
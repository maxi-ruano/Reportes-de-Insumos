@extends('layouts.templeate')
@section('titlePage', "Motivos")

@section('content')
<!-- page content -->
<div class="container">
    @can('view_tramites_habilitados_motivos')
        <div class="col-md-2 col-xs-12">
            <a href="{{route('tramitesHabilitadosMotivos.index')}}?fecha={{date('Y-m-d')}}" class="btn btn-info btn-group-justified"> <i class="fa fa-list"></i> Mostrar listado </a>
        </div>
        <hr>
    @endcan
    
    @if(isset($edit)) 
        {!! Form::open(['route' => ['tramitesHabilitadosMotivos.update', $edit], 'id'=>'formTramitesHabilitadosMotivos', 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    @else
        {!! Form::open(['route' => 'tramitesHabilitadosMotivos.store', 'id'=>'formTramitesHabilitadosMotivos', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

        <div class="form-group">
            <div class="col-md-12 col-xs-12">
                {!! Form::label('description', ' Motivo') !!}
                {!! Form::text('description', isset($edit) ? $edit->description : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el motivo', 'required' => 'required']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-xs-12">
                {!! Form::label('sucursal_id', 'Sucursal: ') !!}
                {!! Form::select('sucursal_id', $sucursales, isset($edit) ? $edit->sucursal_id : 0 , ['class' => 'form-control', 'placeholder' => 'Seleccione']) !!}
            </div>
            <div class="col-md-6 col-xs-12">
                {!! Form::label('limite', 'Limite por día: ') !!}
                {!! Form::number('limite', isset($edit) ? $edit->limite : null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <hr>

        <div class="col-md-2 col-xs-12">
            <button type="submit" class="btn btn-primary btn-group-justified"> <i class="fa fa-check-square-o"></i> Enviar </button>                
        </div>
       
    {!! Form::close() !!}
</div>

<!-- /page content -->
@endsection
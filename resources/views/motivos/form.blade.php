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
            {!! Form::label('description', ' Motivo') !!}
            {!! Form::text('description', isset($edit) ? $edit->description : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el motivo', 'required' => 'required']) !!}
        </div>
        <hr>

        <div class="col-md-2 col-xs-12">
            <button type="submit" class="btn btn-primary btn-group-justified"> <i class="fa fa-check-square-o"></i> Enviar </button>                
        </div>
       
    {!! Form::close() !!}
</div>

<!-- /page content -->
@endsection
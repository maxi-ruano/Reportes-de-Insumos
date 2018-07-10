@extends('layouts.templeate')
@section('titlePage', "Tramites Habilitados")
@section('content')
<!-- page content -->

<div class="container">
    <div class="col-md-2 col-xs-12">
        <a href="{{route('tramitesHabilitados.index')}}" class="btn btn-info btn-group-justified"> <i class="fa fa-list"></i> Mostrar listado </a>
    </div>
    <br>
    <hr>
    <h4>Crear Turno</h4>   
    
    @if( isset($edit) ) 
        {!! Form::open(['route' => ['tramitesHabilitados.update', $edit], 'id'=>'formTramitesHabilitados', 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    @else
        {!! Form::open(['route' => 'tramitesHabilitados.store', 'id'=>'formTramitesHabilitados', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
    @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

            <div class="form-group">
                {!! Form::label('fecha', ' Fecha') !!}
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </div>
                    <input type="date" name="fecha" value="{{ isset($edit) ? $edit->fecha : $fecha }}" class="form-control" required="required" readonly ="readonly" >
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('tipo_doc', ' Documento') !!}
                {!! Form::select('tipo_doc', $tdocs, isset($edit) ? $edit->tipo_doc : null, ['class' => 'form-control']) !!}
                {!! Form::number('nro_doc', isset($edit) ? $edit->nro_doc : null, ['class' => 'form-control', 'placeholder' => 'Nro. Documento', 'required' => 'required', 'min' => '0']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('apellido', ' Apellidos') !!}
                {!! Form::text('apellido', isset($edit) ? $edit->apellido : null, ['class' => 'form-control', 'placeholder' => 'Apellidos', 'required' => 'required']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('nombre', ' Nombres') !!}
                {!! Form::text('nombre', isset($edit) ? $edit->nombre : null, ['class' => 'form-control', 'placeholder' => 'Nombres', 'required' => 'required']) !!}
            </div>

            <div class="form-group">                
                {!! Form::label('pais', ' País') !!}
                {!! Form::select('pais', $paises, isset($edit) ? $edit->pais : 1 , ['class' => 'form-control']) !!}
            </div>
            <hr>
            <div class="col-md-2 col-xs-12">
                <button type="submit" class="btn btn-primary btn-group-justified"> <i class="fa fa-check-square-o"></i> Enviar </button>                
            </div>
        {!! Form::close() !!}
</div>

<!-- /page content -->
@endsection

@push('scripts')
  <script src="{{ asset('vendors/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  
@endpush
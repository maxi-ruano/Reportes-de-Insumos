@extends('layouts.templeate')

@section('content')
<!-- page content -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

      <div class="x_content">
                {!! Form::open(['route' => 'bedel.index', 'id'=>'formCategory', 'method' => 'GET', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
                <input type="text" name="op" id="op" value="find" class="hide">
                <div class="form-group">
                    <div class="col-md-1 col-sm-1">
                      <select name="pais" class="form-control" required place-holder="asd">
                        <option value="" disabled selected>Nac.</option>
                        @foreach($default['paises'] as $pais)
                        @if($pais->id == 1)
                        <option value="{{ $pais->id }}" >{{ $pais->description }}</option>
                        @else
                        <option value="{{ $pais->id }}">{{ $pais->description }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-1 col-sm-1">
                      <select name ="tipo_doc" class="form-control" required>
                        @foreach($default['tdoc'] as $tdoc)
                        @if($tdoc->id == 1)
                        <option value="{{ $tdoc->id }}" selected>{{ $tdoc->description }}</option>
                        @else
                        <option value="{{ $tdoc->id }}">{{ $tdoc->description }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-2 col-sm-2">
                      <input name="doc" type="text" class="form-control" placeholder="Documento" required>
                    </div>

                    <div class="col-md-1 col-sm-1">
                      <select name="sexo" class="form-control" required>
                        @foreach($default['sexo'] as $sex)
                        @if($sex->id == 0)
                        <option value="" selected disabled>Sexo</option>
                        @else
                        <option value="{{ strtolower($sex->description) }}">{{ $sex->description }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>


                  <!--<div class="ln_solid"></div>-->

                    <!--<div class="col-md-2 col-sm-2">-->
                      <input id="send" type="submit" class="btn btn-success col-md-1 col-sm-1" value="Enviar">
                    <!--</div>-->


                {!! Form::close() !!}
                @if($categorias[0] != false)
                  <div class="col-md-2 col-sm-2">
                    <select name ="categorias" class="form-control" required>
                      <option value="" selected>Categoria</option>
                      @foreach($categorias[1]->tramite as $cat)
                      <option value="{{ $cat->clase }}">{{ $cat->clase }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-2 col-sm-2">
                    <select name ="computadoras" class="form-control">
                      <option value="" selected>Computadora</option>
                      @if($computadoras[0] != false)
                        @foreach($computadoras[1] as $computadora)
                        <option value="{{ $computadora->id }}">{{ $computadora->id }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <input id="send" type="submit" class="btn btn-success col-md-1 col-sm-1" value="Asignar">
                </div>
                @else
                  <!--<div class="form-group">
                    <div class="panel panel-default">
                      <div class="panel-body"><h3> $peticion[1]->disponibilidadMensaje </h3></div>
                    </div>
                  </div>-->
                @endif
      </div>
      @include('bedel.monitoreo')
    </div>
  </div>
</div>
<!-- /page content -->
@endsection

@section('scripts')
<!-- validator -->
<script src="{{ asset('vendors/validator/validator.js')}}"></script>
@include('includes.scriptForms')

@endsection
